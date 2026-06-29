<?php

namespace App\Http\Controllers\Keuangan;

use App\Http\Controllers\Controller;
use App\Models\AkunKas;
use App\Models\BukuKas;
use Illuminate\Http\Request;

class BukuKasController extends Controller
{
    /**
     * Tampilkan ledger mutasi kas dengan filter dan summary.
     */
    public function index(Request $request)
    {
        // Ambil semua akun kas untuk dropdown filter
        $akunKasList = AkunKas::orderBy('nama_akun')->get();

        // Base query dengan relasi
        $query = BukuKas::with(['akunKas', 'pengguna']);

        // ── Filter: Rekening ──
        if ($request->filled('id_akun')) {
            $query->where('id_akun', $request->id_akun);
        }

        // ── Filter: Tipe (Masuk/Keluar) ──
        if ($request->filled('jenis') && in_array($request->jenis, ['Masuk', 'Keluar'])) {
            $query->where('jenis', $request->jenis);
        }

        // ── Filter: Date Range ──
        if ($request->filled('dari_tanggal')) {
            $query->whereDate('tanggal_transaksi', '>=', $request->dari_tanggal);
        }
        if ($request->filled('sampai_tanggal')) {
            $query->whereDate('tanggal_transaksi', '<=', $request->sampai_tanggal);
        }

        // ── Filter: Search keterangan ──
        if ($request->filled('search')) {
            $query->where('keterangan', 'like', '%'.$request->search.'%');
        }

        // ── Summary Cards ──
        // Clone query untuk summary (sebelum pagination)
        $summaryQuery = clone $query;

        $totalMasuk = (clone $summaryQuery)->where('jenis', 'Masuk')->sum('nominal');
        $totalKeluar = (clone $summaryQuery)->where('jenis', 'Keluar')->sum('nominal');
        $net = $totalMasuk - $totalKeluar;

        // Saldo Awal Periode: total semua transaksi SEBELUM tanggal "dari"
        $saldoAwal = 0;
        if ($request->filled('dari_tanggal')) {
            $saldoAwalQuery = BukuKas::query();

            // Terapkan filter rekening jika ada (saldo per rekening)
            if ($request->filled('id_akun')) {
                $saldoAwalQuery->where('id_akun', $request->id_akun);
            }

            $saldoMasukSebelum = (clone $saldoAwalQuery)
                ->where('jenis', 'Masuk')
                ->whereDate('tanggal_transaksi', '<', $request->dari_tanggal)
                ->sum('nominal');

            $saldoKeluarSebelum = (clone $saldoAwalQuery)
                ->where('jenis', 'Keluar')
                ->whereDate('tanggal_transaksi', '<', $request->dari_tanggal)
                ->sum('nominal');

            $saldoAwal = $saldoMasukSebelum - $saldoKeluarSebelum;
        } else {
            // Jika tidak ada filter tanggal, saldo awal = 0 (awal semua waktu)
            $saldoAwal = 0;
        }

        $saldoAkhir = $saldoAwal + $net;

        // ── Data Tabel + Pagination ──
        $bukuKas = $query->orderBy('tanggal_transaksi', 'desc')
            ->orderBy('id_buku_kas', 'desc')
            ->paginate(20)
            ->withQueryString();

        return view('keuangan.buku-kas.index', compact(
            'akunKasList',
            'bukuKas',
            'totalMasuk',
            'totalKeluar',
            'net',
            'saldoAwal',
            'saldoAkhir',
        ));
    }
}
