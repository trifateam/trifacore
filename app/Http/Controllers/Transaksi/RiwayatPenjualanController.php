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

        // Format data for Alpine Modal Injection
        $alpineData = $penjualans->map(function ($p) {
            $status = 'Lunas';
            $badge = 'success';
            if ($p->metode_pembayaran === 'PIUTANG' && $p->piutang) {
                $status = $p->piutang->status_piutang;
                $badge = $status === 'Lunas' ? 'success' : ($status === 'Belum Lunas' ? 'warning' : 'info');
            }

            return [
                'id' => $p->id_penjualan,
                'no_faktur' => $p->no_faktur_jual,
                'tanggal' => \Carbon\Carbon::parse($p->tanggal_penjualan)->translatedFormat('d M Y H:i'),
                'pelanggan' => $p->pelanggan->nama_lengkap,
                'kasir' => $p->pengguna->nama_lengkap,
                'kategori' => strtoupper($p->kategori_penjualan),
                'total' => $p->total_harga,
                'metode' => $p->metode_pembayaran,
                'status' => $status,
                'badge' => $badge,
                'catatan' => $p->catatan,
                'sisa_piutang' => $p->piutang ? $p->piutang->sisa_piutang : 0,
                'details' => $p->detailPenjualan->map(function ($d) {
                    return [
                        'nama_barang' => $d->barang ? $d->barang->nama_barang : 'Ayam Afkir', // fallback if null
                        'kuantitas' => $d->kuantitas,
                        'harga_satuan' => $d->harga_satuan,
                        'subtotal' => $d->sub_total,
                    ];
                })
            ];
        });

        return view('transaksi.riwayat-penjualan.index', compact(
            'penjualans', 'pelanggans', 'alpineData', 
            'totalTransaksi', 'totalNominal', 'totalTempo'
        ));
    }
}
