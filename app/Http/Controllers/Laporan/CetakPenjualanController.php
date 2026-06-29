<?php

namespace App\Http\Controllers\Laporan;

use App\Http\Controllers\Controller;
use App\Models\Pelanggan;
use App\Models\Penjualan;
use App\Models\Setting;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CetakPenjualanController extends Controller
{
    public function index()
    {
        $pelanggans = Pelanggan::all();

        $years = Penjualan::where('kategori_penjualan', 'Telur')
            ->selectRaw('YEAR(tanggal_penjualan) as year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year');

        if ($years->isEmpty()) {
            $years = collect([date('Y')]);
        }

        return view('laporan.cetak.penjualan-telur-filter', compact('pelanggans', 'years'));
    }

    private function getReportData($pelanggan_id, $bulan, $tahun)
    {
        $settings = Setting::pluck('value', 'key')->toArray();

        $query = Penjualan::with(['detailPenjualan.barang', 'pelanggan'])
            ->where('kategori_penjualan', 'Telur')
            ->whereYear('tanggal_penjualan', $tahun)
            ->whereMonth('tanggal_penjualan', $bulan);

        if ($pelanggan_id && $pelanggan_id !== 'all') {
            $query->where('id_pelanggan', $pelanggan_id);
        }

        $penjualans = $query->orderBy('tanggal_penjualan', 'asc')->get();

        $detailData = [];
        $totalPenjualan = 0;
        $totalQty = 0;

        foreach ($penjualans as $jual) {
            $status = strtolower($jual->metode_pembayaran) == 'tempo' ? 'Belum Lunas/Tempo' : 'Lunas';

            foreach ($jual->detailPenjualan as $detail) {
                $detailData[] = [
                    'no_nota' => $jual->no_faktur_jual,
                    'tanggal' => Carbon::parse($jual->tanggal_penjualan)->format('d-m-Y'),
                    'pelanggan' => $jual->pelanggan ? $jual->pelanggan->nama_lengkap : '-',
                    'jenis_telur' => $detail->barang ? $detail->barang->nama_barang : '-',
                    'qty' => $detail->kuantitas,
                    'harga_unit' => $detail->harga_satuan,
                    'total' => $detail->sub_total,
                    'status' => $status,
                ];

                $totalPenjualan += $detail->sub_total;
                $totalQty += $detail->kuantitas;
            }
        }

        $rataHarga = $totalQty > 0 ? $totalPenjualan / $totalQty : 0;

        $pelanggan = null;
        if ($pelanggan_id && $pelanggan_id !== 'all') {
            $pelanggan = Pelanggan::find($pelanggan_id);
        }

        return compact(
            'settings', 'bulan', 'tahun', 'pelanggan',
            'detailData', 'totalPenjualan', 'totalQty', 'rataHarga'
        );
    }

    public function preview(Request $request)
    {
        $request->validate([
            'pelanggan_id' => 'required',
            'bulan' => 'required',
            'tahun' => 'required',
        ]);

        $data = $this->getReportData($request->pelanggan_id, $request->bulan, $request->tahun);

        return view('laporan.cetak.penjualan-telur', $data);
    }

    public function pdf(Request $request)
    {
        $request->validate([
            'pelanggan_id' => 'required',
            'bulan' => 'required',
            'tahun' => 'required',
        ]);

        $data = $this->getReportData($request->pelanggan_id, $request->bulan, $request->tahun);

        $pdf = Pdf::loadView('laporan.cetak.penjualan-telur', $data)
            ->setPaper('a4', 'landscape');

        $pelangganName = $data['pelanggan'] ? str_replace(' ', '-', $data['pelanggan']->nama_lengkap) : 'Semua-Pelanggan';
        $filename = 'Laporan-Penjualan-Telur-'.$pelangganName.'-'.$request->bulan.'-'.$request->tahun.'.pdf';

        return $pdf->download($filename);
    }
}
