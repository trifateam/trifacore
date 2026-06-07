<?php

namespace App\Http\Controllers\Pencatatan;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\Batch;
use App\Models\Kandang;
use App\Models\KonsumsiPakan;
use App\Models\RiwayatAktivitas;
use App\Services\StokBarangService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class KonsumsiPakanController extends Controller
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

        $kandangData = $kandangs->map(function ($kandang) use ($hariIni) {
            $batches = $kandang->batches->map(function ($batch) use ($hariIni) {
                $jumlahSesiHariIni = KonsumsiPakan::where('id_batch', $batch->id_batch)
                    ->where('tanggal_konsumsi', $hariIni)
                    ->count();

                return [
                    'id_batch' => $batch->id_batch,
                    'nama_batch' => $batch->nama_batch,
                    'jumlah_sisa' => $batch->jumlah_sisa,
                    'jumlah_sesi' => $jumlahSesiHariIni,
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

        return view('pencatatan.konsumsi-pakan.index', compact('kandangData', 'hariIni'));
    }

    /**
     * Tampilkan form input konsumsi pakan baru.
     */
    public function create($id_batch)
    {
        $batch = Batch::with('kandang')->findOrFail($id_batch);
        $hariIni = Carbon::today()->toDateString();

        // Cek apakah sudah 2 sesi
        $jumlahSesiHariIni = KonsumsiPakan::where('id_batch', $id_batch)
            ->where('tanggal_konsumsi', $hariIni)
            ->count();

        if ($jumlahSesiHariIni >= 2) {
            return redirect()->route('pencatatan.konsumsi-pakan.index')
                ->with('error', 'Pencatatan konsumsi pakan untuk batch ini pada hari ini sudah mencapai maksimal (2 sesi).');
        }

        // Ambil data pakan
        $pakanList = Barang::where('kategori_barang', 'Pakan')->get();

        return view('pencatatan.konsumsi-pakan.form', compact('batch', 'hariIni', 'pakanList', 'jumlahSesiHariIni'));
    }

    /**
     * Simpan data konsumsi pakan baru dan update stok.
     */
    public function store(Request $request, $id_batch)
    {
        $request->validate([
            'id_barang' => 'required|exists:barang,id_barang',
            'jumlah_pakan_kg' => 'required|numeric|min:0.01',
            'waktu_pemberian' => 'nullable|date_format:H:i',
        ]);

        $batch = Batch::with('kandang')->findOrFail($id_batch);
        $hariIni = Carbon::today()->toDateString();

        // Cek batas sesi
        $jumlahSesiHariIni = KonsumsiPakan::where('id_batch', $id_batch)
            ->where('tanggal_konsumsi', $hariIni)
            ->count();

        if ($jumlahSesiHariIni >= 2) {
            return redirect()->route('pencatatan.konsumsi-pakan.index')
                ->with('error', 'Pencatatan konsumsi pakan untuk batch ini pada hari ini sudah mencapai maksimal (2 sesi).');
        }

        // Default waktu ke saat ini jika kosong
        $waktuPemberian = $request->waktu_pemberian ?: Carbon::now()->format('H:i');
        
        // Generate Kode Pakan: KP-YYYYMMDD-XX
        $sesiKe = $jumlahSesiHariIni + 1;
        $kodePakan = \App\Helpers\CodeGenerator::generate('KP', 'konsumsi_pakan', 'kode_pakan');

        DB::beginTransaction();
        try {
            // Kurangi stok pakan (Akan throw Exception jika stok tidak cukup)
            $barangPakan = $this->stokBarangService->kurangiStokPakan($request->id_barang, $request->jumlah_pakan_kg);

            KonsumsiPakan::create([
                'kode_pakan' => $kodePakan,
                'id_batch' => $id_batch,
                'id_barang' => $request->id_barang,
                'id_pengguna' => Auth::id(),
                'tanggal_konsumsi' => $hariIni,
                'waktu_pemberian' => $waktuPemberian,
                'jumlah_pakan_kg' => $request->jumlah_pakan_kg,
            ]);

            // Catat Riwayat Aktivitas
            RiwayatAktivitas::create([
                'id_pengguna' => Auth::id(),
                'aktivitas' => "Mencatat konsumsi pakan (Kandang: {$batch->kandang->nama_kandang}, Batch: {$batch->nama_batch}) sebanyak {$request->jumlah_pakan_kg} kg {$barangPakan->nama_barang}.",
            ]);

            DB::commit();

            return redirect()->route('pencatatan.konsumsi-pakan.index')
                ->with('success', "Pencatatan konsumsi pakan (Sesi $sesiKe) berhasil disimpan dan stok dikurangi.");

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Gagal menyimpan pencatatan: ' . $e->getMessage());
        }
    }
}
