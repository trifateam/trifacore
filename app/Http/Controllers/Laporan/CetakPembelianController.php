<?php

namespace App\Http\Controllers\Laporan;

use App\Http\Controllers\Controller;
use App\Models\Pembelian;
use App\Models\Setting;
use App\Models\Supplier;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CetakPembelianController extends Controller
{
    public function index()
    {
        $suppliers = Supplier::all();

        $years = Pembelian::whereHas('detailPembelian.barang', function ($q) {
            $q->whereIn('kategori_barang', ['Pakan', 'Vitamin']);
        })
            ->select('tanggal_pembelian')->get()->map(function ($p) {
                return date('Y', strtotime($p->tanggal_pembelian));
            })->unique()->sortDesc()->values();

        if ($years->isEmpty()) {
            $years = collect([date('Y')]);
        }

        return view('laporan.cetak.pembelian-pakan-filter', compact('suppliers', 'years'));
    }

    private function getReportData($supplier_id, $bulan, $tahun)
    {
        $settings = Setting::pluck('value', 'key')->toArray();

        $query = Pembelian::with(['detailPembelian.barang', 'supplier'])
            ->whereHas('detailPembelian.barang', function ($q) {
                $q->whereIn('kategori_barang', ['Pakan', 'Vitamin']);
            })
            ->whereYear('tanggal_pembelian', $tahun)
            ->whereMonth('tanggal_pembelian', $bulan);

        if ($supplier_id && $supplier_id !== 'all') {
            $query->where('id_supplier', $supplier_id);
        }

        $pembelians = $query->orderBy('tanggal_pembelian', 'asc')->get();

        $detailData = [];
        $totalPembelian = 0;
        $totalQty = 0;

        foreach ($pembelians as $beli) {
            $status = strtolower($beli->metode_pembayaran) == 'tempo' ? 'Belum Lunas/Tempo' : 'Lunas';

            foreach ($beli->detailPembelian as $detail) {
                // Pastikan hanya memproses detail yang kategorinya Pakan atau Vitamin
                if ($detail->barang && in_array($detail->barang->kategori_barang, ['Pakan', 'Vitamin'])) {
                    $detailData[] = [
                        'no_nota' => $beli->no_faktur_beli,
                        'tanggal' => Carbon::parse($beli->tanggal_pembelian)->format('d-m-Y'),
                        'supplier' => $beli->supplier ? $beli->supplier->nama_supplier : '-',
                        'jenis_material' => $detail->barang->nama_barang,
                        'qty' => $detail->kuantitas,
                        'harga_unit' => $detail->harga_satuan,
                        'total' => $detail->subtotal,
                        'status' => $status,
                    ];

                    $totalPembelian += $detail->subtotal;
                    $totalQty += $detail->kuantitas;
                }
            }
        }

        $rataHarga = $totalQty > 0 ? $totalPembelian / $totalQty : 0;

        $supplier = null;
        if ($supplier_id && $supplier_id !== 'all') {
            $supplier = Supplier::find($supplier_id);
        }

        return compact(
            'settings', 'bulan', 'tahun', 'supplier',
            'detailData', 'totalPembelian', 'totalQty', 'rataHarga'
        );
    }

    public function preview(Request $request)
    {
        $request->validate([
            'supplier_id' => 'required',
            'bulan' => 'required',
            'tahun' => 'required',
        ]);

        $data = $this->getReportData($request->supplier_id, $request->bulan, $request->tahun);

        return view('laporan.cetak.pembelian-pakan', $data);
    }

    public function pdf(Request $request)
    {
        $request->validate([
            'supplier_id' => 'required',
            'bulan' => 'required',
            'tahun' => 'required',
        ]);

        $data = $this->getReportData($request->supplier_id, $request->bulan, $request->tahun);

        $pdf = Pdf::loadView('laporan.cetak.pembelian-pakan', $data)
            ->setPaper('a4', 'landscape');

        $supplierName = $data['supplier'] ? str_replace(' ', '-', $data['supplier']->nama_supplier) : 'Semua-Supplier';
        $filename = 'Laporan-Pembelian-Pakan-'.$supplierName.'-'.$request->bulan.'-'.$request->tahun.'.pdf';

        return $pdf->download($filename);
    }
}
