<?php

namespace App\Http\Controllers\Transaksi;

use App\Http\Controllers\Controller;
use App\Models\Pelanggan;
use App\Models\Penjualan;
use Illuminate\Http\Request;

class RiwayatPenjualanController extends Controller
{
    public function index(Request $request)
    {
        $pelanggans = Pelanggan::all();

        // Base Query
        $query = Penjualan::with(['pelanggan', 'pengguna', 'detailPenjualan.barang', 'piutang'])
            ->orderBy('tanggal_penjualan', 'desc');

        // Apply Filters
        if ($request->filled('tanggal_mulai')) {
            $query->whereDate('tanggal_penjualan', '>=', $request->tanggal_mulai);
        }
        if ($request->filled('tanggal_akhir')) {
            $query->whereDate('tanggal_penjualan', '<=', $request->tanggal_akhir);
        }
        if ($request->filled('id_pelanggan')) {
            $query->where('id_pelanggan', $request->id_pelanggan);
        }
        if ($request->filled('kategori_penjualan')) {
            $query->where('kategori_penjualan', $request->kategori_penjualan);
        }

        // Status Filter Logic (LUNAS, TEMPO, LUNAS SEBAGIAN)
        if ($request->filled('status_pembayaran')) {
            $status = $request->status_pembayaran;
            if ($status === 'Lunas') {
                $query->where(function ($q) {
                    $q->where('metode_pembayaran', 'LUNAS')
                        ->orWhereHas('piutang', function ($q) {
                            $q->where('status_piutang', 'Lunas');
                        });
                });
            } elseif ($status === 'Tempo') {
                $query->whereHas('piutang', function ($q) {
                    $q->where('status_piutang', 'Belum Lunas');
                });
            } elseif ($status === 'Lunas Sebagian') {
                $query->whereHas('piutang', function ($q) {
                    $q->where('status_piutang', 'Lunas Sebagian');
                });
            }
        }

        // For Summary Calculation, we need to execute the filtered query before pagination
        $filteredRecords = $query->get();

        $totalTransaksi = $filteredRecords->count();
        $totalNominal = $filteredRecords->sum('total_harga');

        // Total Tempo is sum of sisa_piutang for all records that have piutang
        $totalTempo = $filteredRecords->sum(function ($penjualan) {
            return $penjualan->piutang ? $penjualan->piutang->sisa_piutang : 0;
        });

        // Get Paginated Data
        $penjualans = $query->paginate(15)->withQueryString();

        return view('transaksi.riwayat-penjualan.index', compact(
            'penjualans', 'pelanggans',
            'totalTransaksi', 'totalNominal', 'totalTempo'
        ));
    }

    public function show($id)
    {
        $penjualan = Penjualan::with(['pelanggan', 'pengguna', 'detailPenjualan.barang', 'piutang'])->findOrFail($id);

        return view('transaksi.riwayat-penjualan.show', compact('penjualan'));
    }
}
