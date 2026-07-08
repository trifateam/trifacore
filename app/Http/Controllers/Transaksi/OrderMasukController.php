<?php

namespace App\Http\Controllers\Transaksi;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\Kandang;
use App\Models\Penjualan;
use App\Models\Setting;
use App\Services\AuditService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderMasukController extends Controller
{
    /**
     * Daftar order masuk dari Sales (belum selesai).
     */
    public function index()
    {
        $orders = Penjualan::with(['pelanggan', 'detailPenjualan.barang', 'pengguna', 'penggunaGudang'])
            ->where('status_order', 'Menunggu')
            ->orderBy('tanggal_penjualan', 'asc')
            ->paginate(15)
            ->withQueryString();

        return view('transaksi.order-masuk.index', compact('orders'));
    }

    public function diproses()
    {
        $orders = Penjualan::with(['pelanggan', 'detailPenjualan.barang', 'pengguna', 'penggunaGudang'])
            ->where('status_order', 'Diproses')
            ->orderBy('tanggal_proses', 'desc')
            ->paginate(15)
            ->withQueryString();

        return view('transaksi.order-masuk.diproses', compact('orders'));
    }

    public function selesaiList()
    {
        $orders = Penjualan::with(['pelanggan', 'detailPenjualan.barang', 'pengguna', 'penggunaGudang'])
            ->where('status_order', 'Selesai')
            ->orderBy('updated_at', 'desc')
            ->paginate(15)
            ->withQueryString();

        return view('transaksi.order-masuk.selesai', compact('orders'));
    }

    /**
     * Tandai order sebagai "Diproses".
     */
    public function prosesOrder($id)
    {
        $penjualan = Penjualan::findOrFail($id);

        if ($penjualan->status_order !== 'Menunggu') {
            return back()->with('error', 'Order ini sudah diproses atau selesai.');
        }

        $penjualan->update([
            'status_order' => 'Diproses',
            'id_pengguna_gudang' => Auth::id(),
            'tanggal_proses' => now(),
        ]);

        AuditService::log("Memproses order penjualan {$penjualan->no_faktur_jual}");

        return back()->with('success', "Order {$penjualan->no_faktur_jual} sedang diproses.");
    }

    /**
     * Tandai order sebagai "Selesai" dan kurangi stok/populasi.
     */
    public function selesaiOrder($id)
    {
        $penjualan = Penjualan::with(['detailPenjualan.barang'])->findOrFail($id);

        if (in_array($penjualan->status_order, ['Selesai', 'Dibatalkan'])) {
            return back()->with('error', 'Order ini sudah selesai atau dibatalkan.');
        }

        try {
            DB::transaction(function () use ($penjualan) {
                // Kurangi stok / populasi
                foreach ($penjualan->detailPenjualan as $detail) {
                    if ($penjualan->kategori_penjualan === 'afkir') {
                        // Logika Afkir: Kurangi populasi kandang target
                        if ($penjualan->id_kandang) {
                            $kandang = Kandang::lockForUpdate()->findOrFail($penjualan->id_kandang);
                            if ($kandang->populasi_saat_ini < $detail->kuantitas) {
                                throw new \Exception("Populasi kandang tidak mencukupi. Tersedia: {$kandang->populasi_saat_ini}, Diminta: {$detail->kuantitas}");
                            }
                            $kandang->populasi_saat_ini -= $detail->kuantitas;
                            $kandang->save();
                        }
                    } else {
                        // Logika Telur & Pupuk: Kurangi stok gudang
                        $barang = Barang::lockForUpdate()->findOrFail($detail->id_barang);
                        if ($barang->stok_barang < $detail->kuantitas) {
                            throw new \Exception("Stok {$barang->nama_barang} tidak mencukupi. Tersedia: {$barang->stok_barang}, Diminta: {$detail->kuantitas}");
                        }
                        $barang->stok_barang -= $detail->kuantitas;
                        $barang->save();
                    }
                }

                $penjualan->update([
                    'status_order' => 'Selesai',
                    'id_pengguna_gudang' => Auth::id(),
                ]);

                AuditService::log("Menyelesaikan order penjualan {$penjualan->no_faktur_jual} — stok telah dikurangi");
            });

            return back()->with('success', "Order {$penjualan->no_faktur_jual} telah selesai. Stok gudang telah dikurangi.");
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menyelesaikan order: '.$e->getMessage());
        }
    }

    /**
     * Preview Surat Jalan (HTML — bisa di-print via browser).
     */
    public function cetakSuratJalan($id)
    {
        $penjualan = Penjualan::with(['pelanggan', 'detailPenjualan.barang', 'pengguna'])->findOrFail($id);
        $settings = Setting::pluck('value', 'key')->toArray();

        return view('transaksi.order-masuk.surat-jalan', compact('penjualan', 'settings'));
    }

    /**
     * Download Surat Jalan sebagai PDF.
     */
    public function downloadSuratJalan($id)
    {
        $penjualan = Penjualan::with(['pelanggan', 'detailPenjualan.barang', 'pengguna'])->findOrFail($id);
        $settings = Setting::pluck('value', 'key')->toArray();

        $pdf = Pdf::loadView('transaksi.order-masuk.surat-jalan', compact('penjualan', 'settings'))
            ->setPaper('a4', 'portrait');

        $filename = 'Surat-Jalan-'.$penjualan->no_faktur_jual.'.pdf';

        return $pdf->download($filename);
    }

    /**
     * Batalkan order dari sisi Pegawai Gudang.
     */
    public function batalkanOrder($id)
    {
        $penjualan = Penjualan::findOrFail($id);

        if (in_array($penjualan->status_order, ['Selesai', 'Dibatalkan'])) {
            return back()->with('error', 'Order ini sudah selesai atau dibatalkan.');
        }

        $penjualan->update([
            'status_order' => 'Dibatalkan',
        ]);

        AuditService::log("Membatalkan order penjualan {$penjualan->no_faktur_jual} dari sisi gudang");

        return back()->with('success', "Order {$penjualan->no_faktur_jual} telah dibatalkan.");
    }
}
