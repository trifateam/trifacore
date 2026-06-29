<?php

namespace App\Http\Controllers;

use App\Models\AkunKas;
use App\Models\Barang;
use App\Models\BukuKas;
use App\Models\Kandang;
use App\Models\ProduksiTelur;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // ── 1. Summary Cards ──────────────────────────────────────

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

        // Saldo Kas Total: SUM(saldo) dari semua akun_kas aktif
        // HIDDEN untuk Pegawai Kandang, Sales, Pegawai Gudang
        $saldoKas = null;
        $showSaldoKas = ! $user->hasRole('Pegawai Kandang', 'Sales', 'Pegawai Gudang');
        if ($showSaldoKas) {
            $saldoKas = AkunKas::sum('saldo');
        }

        // ── 2. Grafik Tren Produksi 7 Hari Terakhir ──────────────

        $trendProduksi = ProduksiTelur::select(
            DB::raw('DATE(tanggal_produksi) as tanggal'),
            DB::raw('SUM(jml_telur_rb + jml_telur_mk + jml_telur_mb + jml_telur_pecah) as total')
        )
            ->where('tanggal_produksi', '>=', Carbon::today()->subDays(6))
            ->groupBy(DB::raw('DATE(tanggal_produksi)'))
            ->orderBy('tanggal', 'asc')
            ->get();

        // Fill in missing dates with 0
        $chartLabels = [];
        $chartData = [];
        $yValues = [];
        $xValues = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i)->format('Y-m-d');
            $chartLabels[] = Carbon::today()->subDays($i)->translatedFormat('d M');
            $found = $trendProduksi->firstWhere('tanggal', $date);
            $val = $found ? (int) $found->total : 0;
            $chartData[] = $val;

            $yValues[] = $val;
            $xValues[] = 6 - $i; // x = 0 to 6
        }

        // ── 3. Ringkasan Arus Kas Bulan Ini ──────────────────────
        // HIDDEN untuk selain Admin dan Owner
        $showArusKas = $user->hasRole('Admin', 'Owner');
        $kasMasuk = 0;
        $kasKeluar = 0;
        $kasNet = 0;

        if ($showArusKas) {
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
        }

        // ── 4. Alert Stok Kritis ─────────────────────────────────

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

        return view('dashboard', compact(
            'user',
            'totalPopulasi',
            'produksiHariIni',
            'stokKritis',
            'saldoKas',
            'showSaldoKas',
            'chartLabels',
            'chartData',
            'showArusKas',
            'kasMasuk',
            'kasKeluar',
            'kasNet',
            'barangKritis',
            'pakanKritis',
        ));
    }
}
