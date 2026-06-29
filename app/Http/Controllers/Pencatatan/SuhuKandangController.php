<?php

namespace App\Http\Controllers\Pencatatan;

use App\Helpers\CodeGenerator;
use App\Http\Controllers\Controller;
use App\Models\Kandang;
use App\Models\SuhuKandang;
use App\Services\AuditService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SuhuKandangController extends Controller
{
    /**
     * Tampilkan halaman pilih kandang.
     */
    public function index()
    {
        $hariIni = Carbon::today()->toDateString();

        $kandangs = Kandang::all();

        $kandangData = $kandangs->map(function ($kandang) use ($hariIni) {
            $sudahTercatat = SuhuKandang::where('id_kandang', $kandang->id_kandang)
                ->whereDate('tanggal_waktu', $hariIni)
                ->exists();

            return [
                'id_kandang' => $kandang->id_kandang,
                'nama_kandang' => $kandang->nama_kandang,
                'kapasitas_kandang' => $kandang->kapasitas_kandang,
                'populasi_saat_ini' => $kandang->populasi_saat_ini,
                'sudah_tercatat' => $sudahTercatat,
            ];
        });

        return view('pencatatan.suhu.index', compact('kandangData', 'hariIni'));
    }

    /**
     * Tampilkan form input suhu kandang.
     */
    public function create($id_kandang)
    {
        $kandang = Kandang::findOrFail($id_kandang);
        $hariIni = Carbon::today()->toDateString();

        // Cek maks 1 per hari per kandang
        $sudahTercatat = SuhuKandang::where('id_kandang', $id_kandang)
            ->whereDate('tanggal_waktu', $hariIni)
            ->exists();

        if ($sudahTercatat) {
            return redirect()->route('pencatatan.suhu.index')
                ->with('error', 'Pencatatan suhu untuk kandang ini pada hari ini sudah dilakukan (maks 1x per hari).');
        }

        return view('pencatatan.suhu.form', compact('kandang', 'hariIni'));
    }

    /**
     * Simpan data suhu kandang.
     */
    public function store(Request $request, $id_kandang)
    {
        $kandang = Kandang::findOrFail($id_kandang);
        $hariIni = Carbon::today()->toDateString();

        // Cek duplikat
        $sudahTercatat = SuhuKandang::where('id_kandang', $id_kandang)
            ->whereDate('tanggal_waktu', $hariIni)
            ->exists();

        if ($sudahTercatat) {
            return redirect()->route('pencatatan.suhu.index')
                ->with('error', 'Pencatatan suhu untuk kandang ini pada hari ini sudah dilakukan (maks 1x per hari).');
        }

        $request->validate([
            'tanggal_waktu' => 'required|date',
            'suhu' => 'required|numeric|min:-10|max:60',
            'suhu_min' => 'nullable|numeric|min:-10|max:60',
            'suhu_max' => 'nullable|numeric|min:-10|max:60',
            'kelembaban' => 'nullable|numeric|min:0|max:100',
        ]);

        // Validasi suhu_min < suhu_max
        if ($request->suhu_min !== null && $request->suhu_max !== null) {
            if ($request->suhu_min >= $request->suhu_max) {
                return back()->withInput()->with('error', 'Suhu Minimum harus lebih kecil dari Suhu Maksimum.');
            }
        }

        // Generate kode: SK-YYYYMMDD-XX
        $kodeSuhu = CodeGenerator::generate('SK', 'suhu_kandang', 'kode_suhu');

        DB::beginTransaction();
        try {
            SuhuKandang::create([
                'kode_suhu' => $kodeSuhu,
                'id_kandang' => $id_kandang,
                'id_pengguna' => Auth::id(),
                'tanggal_waktu' => $request->tanggal_waktu,
                'suhu' => $request->suhu,
                'suhu_min' => $request->suhu_min,
                'suhu_max' => $request->suhu_max,
                'kelembaban' => $request->kelembaban,
            ]);

            AuditService::log("Mencatat suhu kandang ({$kandang->nama_kandang}): {$request->suhu}°C".
                    ($request->kelembaban ? ", kelembaban {$request->kelembaban}%" : '').'.');

            DB::commit();

            return redirect()->route('pencatatan.suhu.index')
                ->with('success', "Pencatatan suhu kandang ({$kodeSuhu}) berhasil disimpan.");

        } catch (\Exception $e) {
            DB::rollBack();

            return back()->withInput()->with('error', 'Gagal menyimpan pencatatan: '.$e->getMessage());
        }
    }
}
