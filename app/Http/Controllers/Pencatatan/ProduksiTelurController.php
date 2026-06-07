<?php

namespace App\Http\Controllers\Pencatatan;

use App\Http\Controllers\Controller;
use App\Models\Batch;
use App\Models\Kandang;
use App\Models\ProduksiTelur;
use App\Services\StokBarangService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProduksiTelurController extends Controller
{
    protected $stokBarangService;

    public function __construct(StokBarangService $stokBarangService)
    {
        $this->stokBarangService = $stokBarangService;
    }

    /**
     * Tampilkan halaman pilih kandang dan batch.
     */
    public function index()
    {
        $hariIni = Carbon::today()->toDateString();

        $kandangs = Kandang::where('is_active', true)
            ->with(['batches' => function ($query) {
                $query->where('status_batch', 'Aktif');
            }])
            ->get();

        // Kita map datanya agar mudah dibaca di view
        $kandangData = $kandangs->map(function ($kandang) use ($hariIni) {
            $batches = $kandang->batches->map(function ($batch) use ($hariIni) {
                $produksiHariIni = ProduksiTelur::where('id_batch', $batch->id_batch)
                    ->where('tanggal_produksi', $hariIni)
                    ->first();

                return [
                    'id_batch' => $batch->id_batch,
                    'nama_batch' => $batch->nama_batch,
                    'jumlah_sisa' => $batch->jumlah_sisa,
                    'sudah_tercatat' => $produksiHariIni ? true : false,
                    'id_produksi' => $produksiHariIni ? $produksiHariIni->id_produksi : null,
                ];
            });

            return [
                'id_kandang' => $kandang->id_kandang,
                'nama_kandang' => $kandang->nama_kandang,
                'kapasitas_kandang' => $kandang->kapasitas_kandang,
                'populasi_saat_ini' => $kandang->populasi_saat_ini,
                'batches' => $batches,
            ];
        });

        return view('pencatatan.produksi-telur.index', compact('kandangData', 'hariIni'));
    }

    /**
     * Tampilkan form input produksi telur baru.
     */
    public function create($id_batch)
    {
        $batch = Batch::with('kandang')->findOrFail($id_batch);
        $hariIni = Carbon::today()->toDateString();

        // Cek jika sudah tercatat hari ini
        $produksiHariIni = ProduksiTelur::where('id_batch', $id_batch)
            ->where('tanggal_produksi', $hariIni)
            ->first();

        if ($produksiHariIni) {
            return redirect()->route('pencatatan.produksi-telur.edit', ['batch' => $id_batch, 'produksi' => $produksiHariIni->id_produksi])
                ->with('info', 'Pencatatan produksi telur untuk batch ini hari ini sudah ada. Mengalihkan ke mode edit.');
        }

        return view('pencatatan.produksi-telur.form', compact('batch', 'hariIni'));
    }

    /**
     * Simpan data produksi telur baru dan update stok.
     */
    public function store(Request $request, $id_batch)
    {
        $request->validate([
            'jml_telur_rb' => 'required|integer|min:0',
            'jml_telur_mb' => 'required|integer|min:0',
            'jml_telur_mk' => 'required|integer|min:0',
            'jml_telur_pecah' => 'required|integer|min:0',
            'total_berat_kg' => 'nullable|numeric|min:0',
        ]);

        $totalTelur = $request->jml_telur_rb + $request->jml_telur_mb + $request->jml_telur_mk + $request->jml_telur_pecah;
        if ($totalTelur == 0) {
            return back()->withInput()->with('error', 'Minimal salah satu jenis telur harus diisi lebih dari 0.');
        }

        $batch = Batch::with('kandang')->findOrFail($id_batch);
        $hariIni = Carbon::today()->toDateString();

        // Cek double submit
        if (ProduksiTelur::where('id_batch', $id_batch)->where('tanggal_produksi', $hariIni)->exists()) {
            return back()->with('error', 'Pencatatan produksi telur untuk batch ini pada hari ini sudah dilakukan.');
        }

        // Generate Kode Produksi: PT-YYYYMMDD-XX
        $kodeProduksi = \App\Helpers\CodeGenerator::generate('PT', 'produksi_telur', 'kode_produksi');

        DB::beginTransaction();
        try {
            // Update Stok Barang (akan throw error jika master barang belum ada)
            $this->stokBarangService->tambahStokTelur('Telur RB', $request->jml_telur_rb);
            $this->stokBarangService->tambahStokTelur('Telur MB', $request->jml_telur_mb);
            $this->stokBarangService->tambahStokTelur('Telur MK', $request->jml_telur_mk);
            $this->stokBarangService->tambahStokTelur('Telur Pecah', $request->jml_telur_pecah);

            $produksi = ProduksiTelur::create([
                'kode_produksi' => $kodeProduksi,
                'id_batch' => $id_batch,
                'id_pengguna' => Auth::id(),
                'tanggal_produksi' => $hariIni,
                'jml_telur_rb' => $request->jml_telur_rb,
                'jml_telur_mb' => $request->jml_telur_mb,
                'jml_telur_mk' => $request->jml_telur_mk,
                'jml_telur_pecah' => $request->jml_telur_pecah,
                'total_berat_kg' => $request->total_berat_kg ?? 0,
            ]);

            // Catat Riwayat Aktivitas
            \App\Services\AuditService::log("Mencatat produksi telur (Kandang: {$batch->kandang->nama_kandang}, Batch: {$batch->nama_batch}) sejumlah {$totalTelur} butir.");

            DB::commit();

            return redirect()->route('pencatatan.produksi-telur.index')
                ->with('success', 'Data produksi telur berhasil disimpan dan stok gudang terupdate otomatis.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Gagal menyimpan pencatatan: ' . $e->getMessage());
        }
    }

    /**
     * Tampilkan form edit produksi telur.
     */
    public function edit($id_batch, $id_produksi)
    {
        $batch = Batch::with('kandang')->findOrFail($id_batch);
        $produksi = ProduksiTelur::where('id_batch', $id_batch)->findOrFail($id_produksi);
        
        return view('pencatatan.produksi-telur.form', compact('batch', 'produksi'));
    }

    /**
     * Update data produksi telur dan adjust stok barang.
     */
    public function update(Request $request, $id_batch, $id_produksi)
    {
        $request->validate([
            'jml_telur_rb' => 'required|integer|min:0',
            'jml_telur_mb' => 'required|integer|min:0',
            'jml_telur_mk' => 'required|integer|min:0',
            'jml_telur_pecah' => 'required|integer|min:0',
            'total_berat_kg' => 'nullable|numeric|min:0',
        ]);

        $totalTelur = $request->jml_telur_rb + $request->jml_telur_mb + $request->jml_telur_mk + $request->jml_telur_pecah;
        if ($totalTelur == 0) {
            return back()->withInput()->with('error', 'Minimal salah satu jenis telur harus diisi lebih dari 0.');
        }

        $batch = Batch::with('kandang')->findOrFail($id_batch);
        $produksi = ProduksiTelur::where('id_batch', $id_batch)->findOrFail($id_produksi);

        DB::beginTransaction();
        try {
            // Adjust Stok Barang
            $this->stokBarangService->adjustStokTelur('Telur RB', $produksi->jml_telur_rb, $request->jml_telur_rb);
            $this->stokBarangService->adjustStokTelur('Telur MB', $produksi->jml_telur_mb, $request->jml_telur_mb);
            $this->stokBarangService->adjustStokTelur('Telur MK', $produksi->jml_telur_mk, $request->jml_telur_mk);
            $this->stokBarangService->adjustStokTelur('Telur Pecah', $produksi->jml_telur_pecah, $request->jml_telur_pecah);

            $produksi->update([
                'jml_telur_rb' => $request->jml_telur_rb,
                'jml_telur_mb' => $request->jml_telur_mb,
                'jml_telur_mk' => $request->jml_telur_mk,
                'jml_telur_pecah' => $request->jml_telur_pecah,
                'total_berat_kg' => $request->total_berat_kg ?? 0,
            ]);

            // Catat Riwayat Aktivitas
            \App\Services\AuditService::log("Mengedit data produksi telur (Kandang: {$batch->kandang->nama_kandang}, Batch: {$batch->nama_batch}) menjadi {$totalTelur} butir.");

            DB::commit();

            return redirect()->route('pencatatan.produksi-telur.index')
                ->with('success', 'Data produksi telur berhasil diperbarui dan stok gudang ter-adjust otomatis.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Gagal memperbarui pencatatan: ' . $e->getMessage());
        }
    }
}
