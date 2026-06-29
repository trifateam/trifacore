<?php

namespace App\Http\Controllers\Laporan;

use App\Http\Controllers\Controller;
use App\Models\Operasional;
use App\Models\PembayaranHutang;
use App\Models\PembayaranPiutang;
use App\Models\Pembelian;
use App\Models\Penjualan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LabaRugiController extends Controller
{
    public function index()
    {
        // Get available years from penjualan, pembelian, operasional
        $yearsPenjualan = Penjualan::selectRaw('YEAR(tanggal_penjualan) as year')->distinct()->pluck('year')->toArray();
        $yearsPembelian = Pembelian::selectRaw('YEAR(tanggal_pembelian) as year')->distinct()->pluck('year')->toArray();
        $yearsOperasional = Operasional::selectRaw('YEAR(tanggal_operasional) as year')->distinct()->pluck('year')->toArray();

        $allYears = array_unique(array_merge($yearsPenjualan, $yearsPembelian, $yearsOperasional, [date('Y')]));
        rsort($allYears);

        return view('laporan.laba-rugi.index', [
            'years' => $allYears,
        ]);
    }

    public function generate(Request $request)
    {
        $bulan = $request->input('bulan', date('m'));
        $tahun = $request->input('tahun', date('Y'));

        // --- ARUS KAS MASUK ---

        // Penjualan Cash (metode_pembayaran == 'LUNAS')
        $penjualanCash = Penjualan::whereMonth('tanggal_penjualan', $bulan)
            ->whereYear('tanggal_penjualan', $tahun)
            ->where('metode_pembayaran', 'LUNAS')
            ->select('kategori_penjualan', DB::raw('SUM(total_harga) as total'))
            ->groupBy('kategori_penjualan')
            ->pluck('total', 'kategori_penjualan')
            ->toArray();

        $penjualanTelur = $penjualanCash['Telur'] ?? 0;
        $penjualanAfkir = $penjualanCash['Afkir'] ?? 0;
        $penjualanPupuk = $penjualanCash['Pupuk'] ?? 0;

        // Pelunasan Piutang
        $pelunasanPiutang = PembayaranPiutang::whereMonth('tanggal_pembayaran', $bulan)
            ->whereYear('tanggal_pembayaran', $tahun)
            ->sum('jumlah_bayar');

        $totalKasMasuk = $penjualanTelur + $penjualanAfkir + $penjualanPupuk + $pelunasanPiutang;

        // --- ARUS KAS KELUAR ---

        // Pembelian Cash (metode_pembayaran == 'LUNAS')
        $pembelianCash = Pembelian::whereMonth('tanggal_pembelian', $bulan)
            ->whereYear('tanggal_pembelian', $tahun)
            ->where('metode_pembayaran', 'LUNAS')
            ->select('kategori_pembelian', DB::raw('SUM(total_pembelian) as total'))
            ->groupBy('kategori_pembelian')
            ->pluck('total', 'kategori_pembelian')
            ->toArray();

        $pembelianPakan = $pembelianCash['Pakan'] ?? 0;
        $pembelianVitamin = $pembelianCash['Vitamin'] ?? 0;
        $pembelianPullet = $pembelianCash['Pullet'] ?? 0;

        // Pelunasan Hutang
        $pelunasanHutang = PembayaranHutang::whereMonth('tanggal_pembayaran', $bulan)
            ->whereYear('tanggal_pembayaran', $tahun)
            ->sum('jumlah_bayar');

        // Biaya Operasional (Breakdown)
        $biayaOperasionalItems = Operasional::with('kategoriBiaya')
            ->whereMonth('tanggal_operasional', $bulan)
            ->whereYear('tanggal_operasional', $tahun)
            ->get();

        $operasionalBreakdown = [];
        $totalOperasional = 0;
        foreach ($biayaOperasionalItems as $ops) {
            $kategori = $ops->kategoriBiaya ? $ops->kategoriBiaya->nama_kategori : 'Lainnya';
            if (! isset($operasionalBreakdown[$kategori])) {
                $operasionalBreakdown[$kategori] = 0;
            }
            $operasionalBreakdown[$kategori] += $ops->biaya_operasional;
            $totalOperasional += $ops->biaya_operasional;
        }

        $totalKasKeluar = $pembelianPakan + $pembelianVitamin + $pembelianPullet + $pelunasanHutang + $totalOperasional;

        // --- BOTTOM LINE ---
        $netProfitLoss = $totalKasMasuk - $totalKasKeluar;
        $profitMargin = $totalKasMasuk > 0 ? ($netProfitLoss / $totalKasMasuk) * 100 : 0;

        // Prepare array breakdown operasional for easier view iteration
        $opsArr = [];
        foreach ($operasionalBreakdown as $k => $v) {
            $opsArr[] = [
                'kategori' => $k,
                'total' => 'Rp '.number_format($v, 0, ',', '.'),
            ];
        }

        return response()->json([
            'kas_masuk' => [
                'penjualan_telur' => 'Rp '.number_format($penjualanTelur, 0, ',', '.'),
                'penjualan_afkir' => 'Rp '.number_format($penjualanAfkir, 0, ',', '.'),
                'penjualan_pupuk' => 'Rp '.number_format($penjualanPupuk, 0, ',', '.'),
                'pelunasan_piutang' => 'Rp '.number_format($pelunasanPiutang, 0, ',', '.'),
                'total' => 'Rp '.number_format($totalKasMasuk, 0, ',', '.'),
            ],
            'kas_keluar' => [
                'pembelian_pakan' => 'Rp '.number_format($pembelianPakan, 0, ',', '.'),
                'pembelian_vitamin' => 'Rp '.number_format($pembelianVitamin, 0, ',', '.'),
                'pembelian_pullet' => 'Rp '.number_format($pembelianPullet, 0, ',', '.'),
                'pelunasan_hutang' => 'Rp '.number_format($pelunasanHutang, 0, ',', '.'),
                'operasional_breakdown' => $opsArr,
                'total' => 'Rp '.number_format($totalKasKeluar, 0, ',', '.'),
            ],
            'bottom_line' => [
                'net' => 'Rp '.number_format($netProfitLoss, 0, ',', '.'),
                'net_raw' => $netProfitLoss,
                'margin' => round($profitMargin, 2).'%',
            ],
        ]);
    }
}
