<?php

namespace App\Http\Controllers\Pencatatan;

use App\Http\Controllers\Controller;
use App\Models\Deplesi;
use App\Models\Kandang;
use App\Models\KonsumsiPakan;
use App\Models\KonsumsiVitamin;
use App\Models\ProduksiPupukKandang;
use App\Models\ProduksiTelur;
use App\Models\SuhuKandang;
use Illuminate\Http\Request;

class RiwayatPencatatanController extends Controller
{
    /**
     * Riwayat Produksi Telur — semua kolom dari tabel produksi_telur.
     */
    public function produksiTelur(Request $request)
    {
        $kandangs = Kandang::where('is_active', true)->get();

        $query = ProduksiTelur::with(['batch.kandang', 'pengguna'])
            ->orderBy('tanggal_produksi', 'desc')
            ->orderBy('created_at', 'desc');

        if ($request->filled('tanggal_mulai')) {
            $query->whereDate('tanggal_produksi', '>=', $request->tanggal_mulai);
        }
        if ($request->filled('tanggal_akhir')) {
            $query->whereDate('tanggal_produksi', '<=', $request->tanggal_akhir);
        }
        if ($request->filled('id_kandang')) {
            $query->whereHas('batch', function ($q) use ($request) {
                $q->where('id_kandang', $request->id_kandang);
            });
        }

        $records = $query->paginate(15)->withQueryString();

        return view('pencatatan.riwayat.produksi-telur.index', compact('records', 'kandangs'));
    }

    /**
     * Riwayat Konsumsi Pakan — semua kolom dari tabel konsumsi_pakan.
     */
    public function konsumsiPakan(Request $request)
    {
        $kandangs = Kandang::where('is_active', true)->get();

        $query = KonsumsiPakan::with(['batch.kandang', 'barang', 'pengguna'])
            ->orderBy('tanggal_konsumsi', 'desc')
            ->orderBy('created_at', 'desc');

        if ($request->filled('tanggal_mulai')) {
            $query->whereDate('tanggal_konsumsi', '>=', $request->tanggal_mulai);
        }
        if ($request->filled('tanggal_akhir')) {
            $query->whereDate('tanggal_konsumsi', '<=', $request->tanggal_akhir);
        }
        if ($request->filled('id_kandang')) {
            $query->whereHas('batch', function ($q) use ($request) {
                $q->where('id_kandang', $request->id_kandang);
            });
        }

        $records = $query->paginate(15)->withQueryString();

        return view('pencatatan.riwayat.konsumsi-pakan.index', compact('records', 'kandangs'));
    }

    /**
     * Riwayat Konsumsi Vitamin — semua kolom dari tabel konsumsi_vitamin.
     */
    public function konsumsiVitamin(Request $request)
    {
        $kandangs = Kandang::where('is_active', true)->get();

        $query = KonsumsiVitamin::with(['batch.kandang', 'barang', 'pengguna'])
            ->orderBy('tanggal_konsumsi', 'desc')
            ->orderBy('created_at', 'desc');

        if ($request->filled('tanggal_mulai')) {
            $query->whereDate('tanggal_konsumsi', '>=', $request->tanggal_mulai);
        }
        if ($request->filled('tanggal_akhir')) {
            $query->whereDate('tanggal_konsumsi', '<=', $request->tanggal_akhir);
        }
        if ($request->filled('id_kandang')) {
            $query->whereHas('batch', function ($q) use ($request) {
                $q->where('id_kandang', $request->id_kandang);
            });
        }

        $records = $query->paginate(15)->withQueryString();

        return view('pencatatan.riwayat.konsumsi-vitamin.index', compact('records', 'kandangs'));
    }

    /**
     * Riwayat Deplesi — semua kolom dari tabel deplesi.
     */
    public function deplesi(Request $request)
    {
        $kandangs = Kandang::where('is_active', true)->get();

        $query = Deplesi::with(['batch.kandang', 'pengguna'])
            ->orderBy('tanggal_deplesi', 'desc')
            ->orderBy('created_at', 'desc');

        if ($request->filled('tanggal_mulai')) {
            $query->whereDate('tanggal_deplesi', '>=', $request->tanggal_mulai);
        }
        if ($request->filled('tanggal_akhir')) {
            $query->whereDate('tanggal_deplesi', '<=', $request->tanggal_akhir);
        }
        if ($request->filled('id_kandang')) {
            $query->whereHas('batch', function ($q) use ($request) {
                $q->where('id_kandang', $request->id_kandang);
            });
        }

        $records = $query->paginate(15)->withQueryString();

        return view('pencatatan.riwayat.deplesi.index', compact('records', 'kandangs'));
    }

    /**
     * Riwayat Suhu Kandang — semua kolom dari tabel suhu_kandang.
     */
    public function suhuKandang(Request $request)
    {
        $kandangs = Kandang::where('is_active', true)->get();

        $query = SuhuKandang::with(['kandang', 'pengguna'])
            ->orderBy('tanggal_waktu', 'desc')
            ->orderBy('created_at', 'desc');

        if ($request->filled('tanggal_mulai')) {
            $query->whereDate('tanggal_waktu', '>=', $request->tanggal_mulai);
        }
        if ($request->filled('tanggal_akhir')) {
            $query->whereDate('tanggal_waktu', '<=', $request->tanggal_akhir);
        }
        if ($request->filled('id_kandang')) {
            $query->where('id_kandang', $request->id_kandang);
        }

        $records = $query->paginate(15)->withQueryString();

        return view('pencatatan.riwayat.suhu.index', compact('records', 'kandangs'));
    }

    /**
     * Riwayat Produksi Pupuk — semua kolom dari tabel produksi_pupuk_kandang.
     */
    public function produksiPupuk(Request $request)
    {
        $kandangs = Kandang::where('is_active', true)->get();

        $query = ProduksiPupukKandang::with(['kandang', 'pengguna'])
            ->orderBy('tanggal_kumpul', 'desc')
            ->orderBy('created_at', 'desc');

        if ($request->filled('tanggal_mulai')) {
            $query->whereDate('tanggal_kumpul', '>=', $request->tanggal_mulai);
        }
        if ($request->filled('tanggal_akhir')) {
            $query->whereDate('tanggal_kumpul', '<=', $request->tanggal_akhir);
        }
        if ($request->filled('id_kandang')) {
            $query->where('id_kandang', $request->id_kandang);
        }

        $records = $query->paginate(15)->withQueryString();

        return view('pencatatan.riwayat.pupuk.index', compact('records', 'kandangs'));
    }
}
