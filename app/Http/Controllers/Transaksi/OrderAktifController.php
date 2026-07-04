<?php

namespace App\Http\Controllers\Transaksi;

use App\Http\Controllers\Controller;
use App\Models\Penjualan;
use App\Models\Setting;
use Barryvdh\DomPDF\Facade\Pdf;

class OrderAktifController extends Controller
{
    /**
     * Daftar order aktif (belum selesai diproses gudang).
     */
    public function index()
    {
        $orders = Penjualan::with(['pelanggan', 'detailPenjualan.barang', 'pengguna', 'piutang'])
            ->where('status_order', '!=', 'Selesai')
            ->orderBy('tanggal_penjualan', 'desc')
            ->paginate(15)
            ->withQueryString();

        return view('transaksi.order-aktif.index', compact('orders'));
    }

    /**
     * Preview Nota Penjualan (HTML — bisa di-print via browser).
     */
    public function cetakNota($id)
    {
        $penjualan = Penjualan::with(['pelanggan', 'detailPenjualan.barang', 'pengguna'])->findOrFail($id);
        $settings = Setting::pluck('value', 'key')->toArray();

        return view('transaksi.order-aktif.nota', compact('penjualan', 'settings'));
    }

    /**
     * Download Nota Penjualan sebagai PDF.
     */
    public function downloadNota($id)
    {
        $penjualan = Penjualan::with(['pelanggan', 'detailPenjualan.barang', 'pengguna'])->findOrFail($id);
        $settings = Setting::pluck('value', 'key')->toArray();

        $pdf = Pdf::loadView('transaksi.order-aktif.nota', compact('penjualan', 'settings'))
            ->setPaper('a4', 'portrait');

        $filename = 'Nota-Penjualan-'.$penjualan->no_faktur_jual.'.pdf';

        return $pdf->download($filename);
    }
}
