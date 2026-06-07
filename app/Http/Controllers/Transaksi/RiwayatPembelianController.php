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

        // Format data for Alpine Modal Injection
        $alpineData = $pembelians->map(function ($p) {
            $status = 'Lunas';
            $badge = 'success';
            if ($p->metode_pembayaran === 'TEMPO' && $p->hutang) {
                $status = $p->hutang->status_hutang;
                $badge = $status === 'Lunas' ? 'success' : ($status === 'Belum Lunas' ? 'warning' : 'info');
            }

            return [
                'id' => $p->id_pembelian,
                'no_faktur' => $p->no_faktur_beli,
                'tanggal' => \Carbon\Carbon::parse($p->tanggal_pembelian)->translatedFormat('d M Y H:i'),
                'supplier' => $p->supplier->nama_supplier,
                'kasir' => $p->pengguna->nama_lengkap,
                'kategori' => strtoupper($p->kategori_pembelian),
                'total' => $p->total_pembelian,
                'metode' => $p->metode_pembayaran,
                'status' => $status,
                'badge' => $badge,
                'catatan' => $p->catatan,
                'sisa_hutang' => $p->hutang ? $p->hutang->sisa_hutang : 0,
                'details' => $p->detailPembelian->map(function ($d) {
                    return [
                        'nama_barang' => $d->barang ? $d->barang->nama_barang : 'Barang Terhapus',
                        'kuantitas' => $d->kuantitas,
                        'harga_satuan' => $d->harga_satuan,
                        'subtotal' => $d->subtotal, // column name in detail_pembelian is subtotal (no underscore)
                    ];
                })
            ];
        });

        return view('transaksi.riwayat-pembelian.index', compact(
            'pembelians', 'suppliers', 'alpineData', 
            'totalTransaksi', 'totalNominal', 'totalTempo'
        ));
    }
}
