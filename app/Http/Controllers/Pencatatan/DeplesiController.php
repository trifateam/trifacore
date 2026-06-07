<?php

namespace App\Http\Controllers\Pencatatan;

use App\Http\Controllers\Controller;
use App\Models\Batch;
use App\Models\Deplesi;
use App\Models\Kandang;
use App\Models\RiwayatAktivitas;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DeplesiController extends Controller
{
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
                $sudahTercatat = Deplesi::where('id_batch', $batch->id_batch)
                    ->where('tanggal_deplesi', $hariIni)
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

        return view('pencatatan.deplesi.index', compact('kandangData', 'hariIni'));
    }

    /**
     * Tampilkan form input deplesi baru.
     */
    public function create($id_batch)
    {
        $batch = Batch::with('kandang')->findOrFail($id_batch);
        $hariIni = Carbon::today()->toDateString();

        // Cek apakah sudah ada pencatatan hari ini (maks 1x per hari per batch)
        $sudahTercatat = Deplesi::where('id_batch', $id_batch)
            ->where('tanggal_deplesi', $hariIni)
            ->exists();

        if ($sudahTercatat) {
            return redirect()->route('pencatatan.deplesi.index')
                ->with('error', 'Pencatatan deplesi untuk batch ini pada hari ini sudah dilakukan (maks 1x per hari).');
        }

        $populasiSaatIni = $batch->kandang->populasi_saat_ini;

        return view('pencatatan.deplesi.form', compact('batch', 'hariIni', 'populasiSaatIni'));
    }

    /**
     * Simpan data deplesi baru dan update populasi kandang.
     */
    public function store(Request $request, $id_batch)
    {
        $batch = Batch::with('kandang')->findOrFail($id_batch);
        $hariIni = Carbon::today()->toDateString();
        $populasiSaatIni = $batch->kandang->populasi_saat_ini;

        // Cek duplikat hari ini
        $sudahTercatat = Deplesi::where('id_batch', $id_batch)
            ->where('tanggal_deplesi', $hariIni)
            ->exists();

        if ($sudahTercatat) {
            return redirect()->route('pencatatan.deplesi.index')
                ->with('error', 'Pencatatan deplesi untuk batch ini pada hari ini sudah dilakukan (maks 1x per hari).');
        }

        $request->validate([
            'jml_mati' => 'required|integer|min:0',
            'jml_afkir' => 'required|integer|min:0',
        ]);

        $totalDeplesi = $request->jml_mati + $request->jml_afkir;

        // Validasi: minimal 1 kategori harus > 0
        if ($totalDeplesi <= 0) {
            return back()->withInput()->with('error', 'Minimal salah satu kategori (Mati atau Afkir) harus lebih dari 0.');
        }

        // Validasi: total deplesi tidak boleh melebihi populasi
        if ($totalDeplesi > $populasiSaatIni) {
            return back()->withInput()->with('error', "Total deplesi ({$totalDeplesi} ekor) melebihi populasi saat ini ({$populasiSaatIni} ekor).");
        }

        // Generate Kode Deplesi: DP-YYYYMMDD-XX
        $kodeDeplesi = \App\Helpers\CodeGenerator::generate('DP', 'deplesi', 'kode_deplesi');

        DB::beginTransaction();
        try {
            // Simpan data deplesi
            Deplesi::create([
                'kode_deplesi' => $kodeDeplesi,
                'id_batch' => $id_batch,
                'id_pengguna' => Auth::id(),
                'tanggal_deplesi' => $hariIni,
                'jml_mati' => $request->jml_mati,
                'jml_afkir' => $request->jml_afkir,
            ]);

            // Kurangi populasi kandang
            $kandang = $batch->kandang;
            $kandang->populasi_saat_ini -= $totalDeplesi;
            $kandang->save();

            // Catat Riwayat Aktivitas
            $detailParts = [];
            if ($request->jml_mati > 0) {
                $detailParts[] = "{$request->jml_mati} mati";
            }
            if ($request->jml_afkir > 0) {
                $detailParts[] = "{$request->jml_afkir} afkir";
            }
            $detailText = implode(', ', $detailParts);

            RiwayatAktivitas::create([
                'id_pengguna' => Auth::id(),
                'aktivitas' => "Mencatat deplesi (Kandang: {$kandang->nama_kandang}, Batch: {$batch->nama_batch}) sebanyak {$totalDeplesi} ekor ({$detailText}). Populasi tersisa: {$kandang->populasi_saat_ini} ekor.",
            ]);

            DB::commit();

            return redirect()->route('pencatatan.deplesi.index')
                ->with('success', "Pencatatan deplesi ({$kodeDeplesi}) berhasil. Populasi kandang dikurangi {$totalDeplesi} ekor.");

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Gagal menyimpan pencatatan: ' . $e->getMessage());
        }
    }
}
