<?php

namespace App\Http\Controllers\Transaksi;

use App\Http\Controllers\Controller;
use App\Models\Supplier;
use App\Models\Pembelian;
use Illuminate\Http\Request;

class RiwayatPembelianController extends Controller
{
    public function index(Request $request)
    {
        $suppliers = Supplier::all();

        // Base Query
        $query = Pembelian::with(['supplier', 'pengguna', 'detailPembelian.barang', 'hutang'])
            ->orderBy('tanggal_pembelian', 'desc');

        // Apply Filters
        if ($request->filled('tanggal_mulai')) {
            $query->whereDate('tanggal_pembelian', '>=', $request->tanggal_mulai);
        }
        if ($request->filled('tanggal_akhir')) {
            $query->whereDate('tanggal_pembelian', '<=', $request->tanggal_akhir);
        }
        if ($request->filled('id_supplier')) {
            $query->where('id_supplier', $request->id_supplier);
        }

        // Status Filter Logic (LUNAS, TEMPO, LUNAS SEBAGIAN)
        if ($request->filled('status_pembayaran')) {
            $status = $request->status_pembayaran;
            if ($status === 'Lunas') {
                $query->where(function ($q) {
                    $q->where('metode_pembayaran', 'LUNAS')
                      ->orWhereHas('hutang', function ($q) {
                          $q->where('status_hutang', 'Lunas');
                      });
                });
            } elseif ($status === 'Tempo') {
                $query->whereHas('hutang', function ($q) {
                    $q->where('status_hutang', 'Belum Lunas');
                });
            } elseif ($status === 'Lunas Sebagian') {
                $query->whereHas('hutang', function ($q) {
                    $q->where('status_hutang', 'Lunas Sebagian');
                });
            }
        }

        // For Summary Calculation
        $filteredRecords = $query->get();
        
        $totalTransaksi = $filteredRecords->count();
        $totalNominal = $filteredRecords->sum('total_pembelian');
        
        // Total Tempo is sum of sisa_hutang for all records that have hutang
        $totalTempo = $filteredRecords->sum(function ($pembelian) {
            return $pembelian->hutang ? $pembelian->hutang->sisa_hutang : 0;
        });

        // Get Paginated Data
        $pembelians = $query->paginate(15)->withQueryString();

        return view('transaksi.riwayat-pembelian.index', compact(
            'pembelians', 'suppliers',
            'totalTransaksi', 'totalNominal', 'totalTempo'
        ));
    }

    public function show($id)
    {
        $pembelian = Pembelian::with(['supplier', 'pengguna', 'detailPembelian.barang', 'hutang'])->findOrFail($id);
        return view('transaksi.riwayat-pembelian.show', compact('pembelian'));
    }
}
