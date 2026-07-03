<?php

namespace App\Http\Controllers\Pencatatan;

use App\Helpers\CodeGenerator;
use App\Http\Controllers\Controller;
use App\Models\Batch;
use App\Models\Kandang;
use App\Models\ProduksiTelur;
use App\Services\AuditService;
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

        $kandangs = Kandang::query()
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
                    'populasi_saat_ini' => $batch->populasi_saat_ini,
                    'sudah_tercatat' => $produksiHariIni ? true : false,
                    'id_produksi' => $produksiHariIni ? $produksiHariIni->id_produksi : null,
                ];
            });

            return [
                'id_kandang' => $kandang->id_kandang,
                'nama_kandang' => $kandang->nama_kandang,
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

        $lastRecord = ProduksiTelur::where('id_batch', $id_batch)
            ->orderBy('tanggal_produksi', 'desc')
            ->first();

        if ($lastRecord) {
            $startDate = Carbon::parse($lastRecord->tanggal_produksi)->addDay();
        } else {
            $startDate = $batch->updated_at ? Carbon::parse($batch->updated_at)->startOfDay() : Carbon::parse($batch->tgl_masuk);
        }

        $endDate = Carbon::parse($hariIni);
        $days = $startDate->gt($endDate) ? 0 : $startDate->diffInDays($endDate) + 1;

        return view('pencatatan.produksi-telur.form', compact('batch', 'hariIni', 'startDate', 'days'));
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

        // Generate Kode Produksi: PT-YYYYMMDD-XX (Tidak dipakai 1x lagi, di-generate di loop)

        // Logika Backfilling
        $lastRecord = ProduksiTelur::where('id_batch', $id_batch)
            ->orderBy('tanggal_produksi', 'desc')
            ->first();

        if ($lastRecord) {
            $startDate = Carbon::parse($lastRecord->tanggal_produksi)->addDay();
        } else {
            // Jika belum ada pencatatan, mulai dari tanggal assign batch ke kandang (updated_at)
            $startDate = $batch->updated_at ? Carbon::parse($batch->updated_at)->startOfDay() : Carbon::parse($batch->tgl_masuk);
        }

        $endDate = Carbon::parse($hariIni);

        if ($startDate->gt($endDate)) {
            return back()->with('error', 'Pencatatan produksi telur untuk batch ini pada hari ini sudah dilakukan.');
        }

        $days = $startDate->diffInDays($endDate) + 1; // Termasuk hari ini

        DB::beginTransaction();
        try {
            // 1. Update Stok Barang 1x saja dengan total input
            $this->stokBarangService->tambahStokTelur('Telur RB', $request->jml_telur_rb);
            $this->stokBarangService->tambahStokTelur('Telur MB', $request->jml_telur_mb);
            $this->stokBarangService->tambahStokTelur('Telur MK', $request->jml_telur_mk);
            $this->stokBarangService->tambahStokTelur('Telur Pecah', $request->jml_telur_pecah);

            // 2. Hitung base (floor) dan sisa
            $baseRb = floor($request->jml_telur_rb / $days);
            $remRb = $request->jml_telur_rb % $days;

            $baseMb = floor($request->jml_telur_mb / $days);
            $remMb = $request->jml_telur_mb % $days;

            $baseMk = floor($request->jml_telur_mk / $days);
            $remMk = $request->jml_telur_mk % $days;

            $basePecah = floor($request->jml_telur_pecah / $days);
            $remPecah = $request->jml_telur_pecah % $days;

            $totalBerat = $request->total_berat_kg ?? 0;
            $baseBerat = $days > 0 ? $totalBerat / $days : 0; // Float, no remainder

            // 3. Distribusi dan Save
            for ($i = 0; $i < $days; $i++) {
                $currentDate = $startDate->copy()->addDays($i)->toDateString();
                $kodeProduksi = CodeGenerator::generate('PT', 'produksi_telur', 'kode_produksi');

                // Distribusi sisa ke hari terlama (i=0, 1, ...)
                $hariRb = $baseRb + ($i < $remRb ? 1 : 0);
                $hariMb = $baseMb + ($i < $remMb ? 1 : 0);
                $hariMk = $baseMk + ($i < $remMk ? 1 : 0);
                $hariPecah = $basePecah + ($i < $remPecah ? 1 : 0);

                ProduksiTelur::create([
                    'kode_produksi' => $kodeProduksi,
                    'id_batch' => $id_batch,
                    'id_pengguna' => Auth::id(),
                    'tanggal_produksi' => $currentDate,
                    'jml_telur_rb' => $hariRb,
                    'jml_telur_mb' => $hariMb,
                    'jml_telur_mk' => $hariMk,
                    'jml_telur_pecah' => $hariPecah,
                    'total_berat_kg' => $baseBerat,
                ]);
            }

            // Catat Riwayat Aktivitas
            AuditService::log("Mencatat produksi telur (Kandang: {$batch->kandang->nama_kandang}, Batch: {$batch->nama_batch}) sejumlah {$totalTelur} butir".($days > 1 ? " (didistribusikan ke {$days} hari yang terlewat)." : '.'));

            DB::commit();

            return redirect()->route('pencatatan.produksi-telur.index')
                ->with('success', 'Data produksi telur berhasil disimpan dan stok gudang terupdate otomatis'.($days > 1 ? " (dibagi rata ke {$days} hari yang terlewat)." : '.'));

        } catch (\Exception $e) {
            DB::rollBack();

            return back()->withInput()->with('error', 'Gagal menyimpan pencatatan: '.$e->getMessage());
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
            AuditService::log("Mengedit data produksi telur (Kandang: {$batch->kandang->nama_kandang}, Batch: {$batch->nama_batch}) menjadi {$totalTelur} butir.");

            DB::commit();

            return redirect()->route('pencatatan.produksi-telur.index')
                ->with('success', 'Data produksi telur berhasil diperbarui dan stok gudang ter-adjust otomatis.');

        } catch (\Exception $e) {
            DB::rollBack();

            return back()->withInput()->with('error', 'Gagal memperbarui pencatatan: '.$e->getMessage());
        }
    }
}
