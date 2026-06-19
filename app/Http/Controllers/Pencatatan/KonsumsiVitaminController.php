<?php

namespace App\Http\Controllers\Pencatatan;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\Batch;
use App\Models\Kandang;
use App\Models\KonsumsiVitamin;
use App\Services\StokBarangService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class KonsumsiVitaminController extends Controller
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

        $kandangData = $kandangs->map(function ($kandang) use ($hariIni) {
            $batches = $kandang->batches->map(function ($batch) use ($hariIni) {
                $sudahTercatat = KonsumsiVitamin::where('id_batch', $batch->id_batch)
                    ->where('tanggal_konsumsi', $hariIni)
                    ->exists();

                return [
                    'id_batch' => $batch->id_batch,
                    'nama_batch' => $batch->nama_batch,
                    'jumlah_sisa' => $batch->jumlah_sisa,
                    'sudah_tercatat' => $sudahTercatat,
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

        return view('pencatatan.konsumsi-vitamin.index', compact('kandangData', 'hariIni'));
    }

    /**
     * Tampilkan form input konsumsi vitamin baru.
     */
    public function create($id_batch)
    {
        $batch = Batch::with('kandang')->findOrFail($id_batch);
        $hariIni = Carbon::today()->toDateString();

        // Ambil data vitamin dari tabel barang
        $vitaminList = Barang::where('kategori_barang', 'Vitamin')->get();

        return view('pencatatan.konsumsi-vitamin.form', compact('batch', 'hariIni', 'vitaminList'));
    }

    /**
     * Simpan data konsumsi vitamin baru dan update stok.
     */
    public function store(Request $request, $id_batch)
    {
        $request->validate([
            'id_barang' => 'required|exists:barang,id_barang',
            'dosis' => 'nullable|numeric|min:0',
            'total_penggunaan' => 'required|numeric|min:0.01',
            'metode_pemberian' => 'nullable|in:Air Minum,Pakan,Suntik',
            'waktu_pemberian' => 'nullable|date_format:H:i',
        ]);

        $batch = Batch::with('kandang')->findOrFail($id_batch);
        $hariIni = Carbon::today()->toDateString();

        // Default waktu ke saat ini jika kosong
        $waktuPemberian = $request->waktu_pemberian ?: Carbon::now()->format('H:i');

        // Generate Kode Vitamin: KV-YYYYMMDD-XX
        $kodeVitamin = \App\Helpers\CodeGenerator::generate('KV', 'konsumsi_vitamin', 'kode_vitamin');

        DB::beginTransaction();
        try {
            // Kurangi stok vitamin (akan throw Exception jika stok tidak cukup)
            $barangVitamin = $this->stokBarangService->kurangiStokVitamin($request->id_barang, $request->total_penggunaan);

            KonsumsiVitamin::create([
                'kode_vitamin' => $kodeVitamin,
                'id_batch' => $id_batch,
                'id_barang' => $request->id_barang,
                'id_pengguna' => Auth::id(),
                'tanggal_konsumsi' => $hariIni,
                'waktu_pemberian' => $waktuPemberian,
                'dosis' => $request->dosis ?? 0,
                'total_penggunaan' => $request->total_penggunaan,
                'metode_pemberian' => $request->metode_pemberian,
            ]);

            // Catat Riwayat Aktivitas
            $metodeTeks = $request->metode_pemberian ? " via {$request->metode_pemberian}" : "";
            \App\Services\AuditService::log("Mencatat konsumsi vitamin (Kandang: {$batch->kandang->nama_kandang}, Batch: {$batch->nama_batch}) sebanyak {$request->total_penggunaan} {$barangVitamin->satuan} {$barangVitamin->nama_barang}{$metodeTeks}.");

            DB::commit();

            return redirect()->route('pencatatan.konsumsi-vitamin.index')
                ->with('success', "Pencatatan konsumsi vitamin ({$kodeVitamin}) berhasil disimpan dan stok dikurangi.");

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Gagal menyimpan pencatatan: ' . $e->getMessage());
        }
    }
}
