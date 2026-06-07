<?php

namespace App\Http\Controllers\Pencatatan;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\Kandang;
use App\Models\ProduksiPupukKandang;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProduksiPupukController extends Controller
{
    /**
     * Tampilkan halaman pilih kandang.
     */
    public function index()
    {
        $hariIni = Carbon::today()->toDateString();

        $kandangs = Kandang::where('is_active', true)->get();

        $kandangData = $kandangs->map(function ($kandang) use ($hariIni) {
            $sudahTercatat = ProduksiPupukKandang::where('id_kandang', $kandang->id_kandang)
                ->where('tanggal_kumpul', $hariIni)
                ->exists();

            return [
                'id_kandang' => $kandang->id_kandang,
                'nama_kandang' => $kandang->nama_kandang,
                'kapasitas_kandang' => $kandang->kapasitas_kandang,
                'populasi_saat_ini' => $kandang->populasi_saat_ini,
                'sudah_tercatat' => $sudahTercatat,
            ];
        });

        return view('pencatatan.pupuk.index', compact('kandangData', 'hariIni'));
    }

    /**
     * Tampilkan form input produksi pupuk.
     */
    public function create($id_kandang)
    {
        $kandang = Kandang::findOrFail($id_kandang);
        $hariIni = Carbon::today()->toDateString();

        return view('pencatatan.pupuk.form', compact('kandang', 'hariIni'));
    }

    /**
     * Simpan data produksi pupuk dan update stok.
     */
    public function store(Request $request, $id_kandang)
    {
        $kandang = Kandang::findOrFail($id_kandang);

        $request->validate([
            'tanggal_kumpul' => 'required|date',
            'jumlah_karung' => 'required|integer|min:0',
            'total_berat_kg' => 'required|numeric|min:0',
        ]);

        // Minimal salah satu harus > 0
        if ($request->jumlah_karung <= 0 && $request->total_berat_kg <= 0) {
            return back()->withInput()->with('error', 'Minimal jumlah karung atau total berat harus lebih dari 0.');
        }

        // Generate kode: PP-YYYYMMDD-XX
        $kodePupuk = \App\Helpers\CodeGenerator::generate('PP', 'produksi_pupuk_kandang', 'kode_pupuk');

        DB::beginTransaction();
        try {
            ProduksiPupukKandang::create([
                'kode_pupuk' => $kodePupuk,
                'id_kandang' => $id_kandang,
                'id_pengguna' => Auth::id(),
                'tanggal_kumpul' => $request->tanggal_kumpul,
                'jumlah_karung' => $request->jumlah_karung,
                'total_berat_kg' => $request->total_berat_kg,
                'tanggal_catat' => Carbon::now(),
            ]);

            // Tambah stok pupuk di tabel barang
            $stokInfo = '';
            if ($request->total_berat_kg > 0) {
                $barangPupuk = Barang::where('kategori_barang', 'Pupuk')->first();
                if ($barangPupuk) {
                    $barangPupuk->stok_barang += $request->total_berat_kg;
                    $barangPupuk->save();
                    $stokInfo = " Stok pupuk bertambah {$request->total_berat_kg} {$barangPupuk->satuan}.";
                }
            }

            \App\Services\AuditService::log("Mencatat produksi pupuk kandang ({$kandang->nama_kandang}): {$request->jumlah_karung} karung, {$request->total_berat_kg} kg.{$stokInfo}");

            DB::commit();

            return redirect()->route('pencatatan.pupuk.index')
                ->with('success', "Pencatatan produksi pupuk ({$kodePupuk}) berhasil disimpan.{$stokInfo}");

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Gagal menyimpan pencatatan: ' . $e->getMessage());
        }
    }
}
