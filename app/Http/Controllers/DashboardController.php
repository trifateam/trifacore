<?php

namespace App\Http\Controllers;

use App\Models\AkunKas;
use App\Models\Barang;
use App\Models\BukuKas;
use App\Models\Kandang;
use App\Models\Pembelian;
use App\Models\ProduksiTelur;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->hasRole('Admin', 'Owner')) {
            return $this->adminDashboard($user);
        } elseif ($user->hasRole('Pegawai Gudang')) {
            return $this->gudangDashboard($user);
        } elseif ($user->hasRole('Pegawai Kandang')) {
            return $this->kandangDashboard($user);
        } elseif ($user->hasRole('Sales')) {
            return $this->salesDashboard($user);
        }

        abort(403, 'Unauthorized action.');
    }

    private function adminDashboard($user)
    {
        // Total Populasi Ayam: SUM(populasi_saat_ini) dari semua kandang aktif
        $totalPopulasi = Kandang::sum('populasi_saat_ini');

        // Produksi Telur Hari Ini
        $today = Carbon::today();
        $produksiHariIni = ProduksiTelur::whereDate('tanggal_produksi', $today)
            ->selectRaw('COALESCE(SUM(jml_telur_rb + jml_telur_mk + jml_telur_mb + jml_telur_pecah), 0) as total')
            ->value('total');

        // Stok Kritis: COUNT barang WHERE stok_barang < stok_minimum AND stok_minimum > 0
        $stokKritis = Barang::whereColumn('stok_barang', '<', 'stok_minimum')
            ->where('stok_minimum', '>', 0)
            ->count();

        $saldoKas = AkunKas::sum('saldo');

        $trendProduksi = ProduksiTelur::select(
            DB::raw('DATE(tanggal_produksi) as tanggal'),
            DB::raw('SUM(jml_telur_rb + jml_telur_mk + jml_telur_mb + jml_telur_pecah) as total')
        )
            ->where('tanggal_produksi', '>=', Carbon::today()->subDays(6))
            ->groupBy(DB::raw('DATE(tanggal_produksi)'))
            ->orderBy('tanggal', 'asc')
            ->get();

        $chartLabels = [];
        $chartData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i)->format('Y-m-d');
            $chartLabels[] = Carbon::today()->subDays($i)->translatedFormat('d M');
            $found = $trendProduksi->firstWhere('tanggal', $date);
            $chartData[] = $found ? (int) $found->total : 0;
        }

        $bulanIni = Carbon::now();
        $kasMasuk = BukuKas::where('jenis', 'Masuk')
            ->whereMonth('tanggal_transaksi', $bulanIni->month)
            ->whereYear('tanggal_transaksi', $bulanIni->year)
            ->sum('nominal');

        $kasKeluar = BukuKas::where('jenis', 'Keluar')
            ->whereMonth('tanggal_transaksi', $bulanIni->month)
            ->whereYear('tanggal_transaksi', $bulanIni->year)
            ->sum('nominal');

        $kasNet = $kasMasuk - $kasKeluar;

        $barangKritis = Barang::whereColumn('stok_barang', '<', 'stok_minimum')
            ->where('stok_minimum', '>', 0)
            ->select('nama_barang', 'stok_barang', 'stok_minimum', 'satuan')
            ->orderByRaw('(stok_minimum - stok_barang) DESC')
            ->get();

        $pakanKritis = Barang::whereColumn('stok_barang', '<', 'stok_minimum')
            ->where('stok_minimum', '>', 0)
            ->where('kategori_barang', 'Pakan')
            ->select('nama_barang', 'stok_barang', 'stok_minimum', 'satuan')
            ->orderByRaw('(stok_minimum - stok_barang) DESC')
            ->get();

        return view('dashboard.admin', compact(
            'user',
            'totalPopulasi',
            'produksiHariIni',
            'stokKritis',
            'saldoKas',
            'chartLabels',
            'chartData',
            'kasMasuk',
            'kasKeluar',
            'kasNet',
            'barangKritis',
            'pakanKritis',
        ));
    }

    private function gudangDashboard($user)
    {
        // Stok Kritis (semua barang yang dikelola gudang)
        $barangKritis = Barang::whereColumn('stok_barang', '<', 'stok_minimum')
            ->where('stok_minimum', '>', 0)
            ->select('nama_barang', 'stok_barang', 'stok_minimum', 'satuan')
            ->orderByRaw('(stok_minimum - stok_barang) DESC')
            ->get();

        $stokKritisCount = $barangKritis->count();

        // Data Penerimaan Barang Bulan Ini
        $bulanIni = Carbon::now();
        $query = Pembelian::with('hutang')
            ->whereMonth('tanggal_pembelian', $bulanIni->month)
            ->whereYear('tanggal_pembelian', $bulanIni->year);

        $pembelians = $query->get();
        $totalTransaksi = $pembelians->count();
        $totalNominal = $pembelians->sum('total_pembelian');
        $totalTempo = $pembelians->sum(function ($pembelian) {
            return $pembelian->hutang ? $pembelian->hutang->sisa_hutang : 0;
        });

        return view('dashboard.pegawai-gudang', compact(
            'user',
            'barangKritis',
            'stokKritisCount',
            'totalTransaksi',
            'totalNominal',
            'totalTempo'
        ));
    }

    private function kandangDashboard($user)
    {
        $totalPopulasi = Kandang::sum('populasi_saat_ini');

        $today = Carbon::today();
        $produksiHariIni = ProduksiTelur::whereDate('tanggal_produksi', $today)
            ->selectRaw('COALESCE(SUM(jml_telur_rb + jml_telur_mk + jml_telur_mb + jml_telur_pecah), 0) as total')
            ->value('total');

        $pakanKritis = Barang::whereColumn('stok_barang', '<', 'stok_minimum')
            ->where('stok_minimum', '>', 0)
            ->where('kategori_barang', 'Pakan')
            ->select('nama_barang', 'stok_barang', 'stok_minimum', 'satuan')
            ->orderByRaw('(stok_minimum - stok_barang) DESC')
            ->get();

        $trendProduksi = ProduksiTelur::select(
            DB::raw('DATE(tanggal_produksi) as tanggal'),
            DB::raw('SUM(jml_telur_rb + jml_telur_mk + jml_telur_mb + jml_telur_pecah) as total')
        )
            ->where('tanggal_produksi', '>=', Carbon::today()->subDays(6))
            ->groupBy(DB::raw('DATE(tanggal_produksi)'))
            ->orderBy('tanggal', 'asc')
            ->get();

        $chartLabels = [];
        $chartData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i)->format('Y-m-d');
            $chartLabels[] = Carbon::today()->subDays($i)->translatedFormat('d M');
            $found = $trendProduksi->firstWhere('tanggal', $date);
            $chartData[] = $found ? (int) $found->total : 0;
        }

        return view('dashboard.pegawai-kandang', compact(
            'user',
            'totalPopulasi',
            'produksiHariIni',
            'pakanKritis',
            'chartLabels',
            'chartData'
        ));
    }

    private function salesDashboard($user)
    {
        return view('dashboard.sales', compact('user'));
    }
}
