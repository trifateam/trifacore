<?php

namespace App\Http\Controllers\Transaksi;

use App\Http\Controllers\Controller;
use App\Models\Pembelian;
use App\Models\Supplier;
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

        // Get Paginated Data
        $pembelians = $query->paginate(15)->withQueryString();

        return view('transaksi.riwayat-pembelian.index', compact(
            'pembelians', 'suppliers'
        ));
    }

    public function show($id)
    {
        $pembelian = Pembelian::with(['supplier', 'pengguna', 'detailPembelian.barang', 'hutang'])->findOrFail($id);

        return view('transaksi.riwayat-pembelian.show', compact('pembelian'));
    }
}
