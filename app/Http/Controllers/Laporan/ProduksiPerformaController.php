<?php

namespace App\Http\Controllers\Laporan;

use App\Http\Controllers\Controller;
use App\Models\Batch;
use App\Models\Deplesi;
use App\Models\Kandang;
use App\Models\Penjualan;
use App\Models\ProduksiTelur;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProduksiPerformaController extends Controller
{
    public function index()
    {
        $kandangs = Kandang::all();

        $years = ProduksiTelur::selectRaw('YEAR(tanggal_produksi) as year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year');

        if ($years->isEmpty()) {
            $years = collect([date('Y')]);
        }

        return view('laporan.produksi-performa.index', compact('kandangs', 'years'));
    }

    public function generate(Request $request)
    {
        $kandangIds = $request->input('kandang_id'); // Array atau null
        $bulan = $request->input('bulan', date('m'));
        $tahun = $request->input('tahun', date('Y'));

        // Base Query: Produksi Telur
        $query = ProduksiTelur::with(['batch.kandang'])
            ->whereMonth('tanggal_produksi', $bulan)
            ->whereYear('tanggal_produksi', $tahun);

        if (! empty($kandangIds) && ! in_array('all', (array) $kandangIds)) {
            $query->whereHas('batch', function ($q) use ($kandangIds) {
                $q->whereIn('id_kandang', (array) $kandangIds);
            });
        }

        $produksiData = $query->orderBy('tanggal_produksi', 'asc')->get();

        // Base Query: Deplesi (Mortalitas + Afkir)
        $deplesiQuery = Deplesi::whereMonth('tanggal_deplesi', $bulan)
            ->whereYear('tanggal_deplesi', $tahun);

        if (! empty($kandangIds) && ! in_array('all', (array) $kandangIds)) {
            $deplesiQuery->whereHas('batch', function ($q) use ($kandangIds) {
                $q->whereIn('id_kandang', (array) $kandangIds);
            });
        }

        $totalMortalitas = $deplesiQuery->sum('jml_mati') + $deplesiQuery->sum('jml_afkir');

        // Base Query: Penjualan
        $penjualanQuery = Penjualan::whereMonth('tanggal_penjualan', $bulan)
            ->whereYear('tanggal_penjualan', $tahun);

        if (! empty($kandangIds) && ! in_array('all', (array) $kandangIds)) {
            $penjualanQuery->whereIn('id_kandang', (array) $kandangIds);
        }

        $estimasiRevenue = $penjualanQuery->sum('total_harga');

        $totalProduksi = 0;
        $totalHDP = 0;
        $hdpCount = 0;

        $dailyProduksi = [];
        $kandangProduksi = [];
        $tableData = [];

        foreach ($produksiData as $prod) {
            $totalButir = $prod->jml_telur_rb + $prod->jml_telur_mb + $prod->jml_telur_mk + $prod->jml_telur_pecah;
            $totalProduksi += $totalButir;

            $kandang = $prod->batch->kandang;
            $namaKandang = $kandang ? $kandang->nama_kandang : 'Tanpa Kandang';

            // Data untuk Chart
            $tgl = $prod->tanggal_produksi->format('Y-m-d');
            if (! isset($dailyProduksi[$tgl])) {
                $dailyProduksi[$tgl] = [];
            }
            if (! isset($dailyProduksi[$tgl][$namaKandang])) {
                $dailyProduksi[$tgl][$namaKandang] = 0;
            }
            $dailyProduksi[$tgl][$namaKandang] += $totalButir;

            if (! isset($kandangProduksi[$namaKandang])) {
                $kandangProduksi[$namaKandang] = 0;
            }
            $kandangProduksi[$namaKandang] += $totalButir;

            // Hitung Populasi Hari Itu (Populasi Awal Batch - Total Deplesi s/d tanggal produksi)
            $deplesiSdTgl = Deplesi::where('id_batch', $prod->id_batch)
                ->where('tanggal_deplesi', '<=', $prod->tanggal_produksi)
                ->sum(DB::raw('jml_mati + jml_afkir'));

            $populasiHariItu = $prod->batch->populasi_awal - $deplesiSdTgl;
            if ($populasiHariItu > 0) {
                $hdp = ($totalButir / $populasiHariItu) * 100;
                $totalHDP += $hdp;
                $hdpCount++;
            } else {
                $hdp = 0;
            }

            $tableData[] = [
                'tanggal' => $prod->tanggal_produksi->format('d M Y'),
                'kandang' => $namaKandang,
                'batch' => $prod->batch->nama_batch,
                'rb' => $prod->jml_telur_rb,
                'mb' => $prod->jml_telur_mb,
                'mk' => $prod->jml_telur_mk,
                'pecah' => $prod->jml_telur_pecah,
                'total' => $totalButir,
                'populasi' => $populasiHariItu,
                'hdp' => round($hdp, 2),
            ];
        }

        $rataHdp = $hdpCount > 0 ? round($totalHDP / $hdpCount, 2) : 0;

        // Siapkan dataset untuk Line Chart
        $dates = array_keys($dailyProduksi);
        sort($dates);

        $kandangNames = array_keys($kandangProduksi);
        $datasetsLine = [];
        $colors = ['#4F46E5', '#10B981', '#F59E0B', '#EF4444', '#8B5CF6', '#EC4899', '#06B6D4'];

        foreach ($kandangNames as $index => $kName) {
            $dataLine = [];
            foreach ($dates as $date) {
                $dataLine[] = isset($dailyProduksi[$date][$kName]) ? $dailyProduksi[$date][$kName] : 0;
            }
            $datasetsLine[] = [
                'label' => $kName,
                'data' => $dataLine,
                'borderColor' => $colors[$index % count($colors)],
                'backgroundColor' => $colors[$index % count($colors)].'20',
                'borderWidth' => 2,
                'fill' => true,
                'tension' => 0.4,
            ];
        }

        $formattedDates = array_map(function ($d) {
            return Carbon::parse($d)->format('d/m');
        }, $dates);

        return response()->json([
            'summary' => [
                'total_produksi' => number_format($totalProduksi, 0, ',', '.'),
                'rata_hdp' => $rataHdp.'%',
                'total_mortalitas' => number_format($totalMortalitas, 0, ',', '.'),
                'estimasi_revenue' => 'Rp '.number_format($estimasiRevenue, 0, ',', '.'),
            ],
            'chart_line' => [
                'labels' => $formattedDates,
                'datasets' => $datasetsLine,
            ],
            'chart_pie' => [
                'labels' => array_keys($kandangProduksi),
                'datasets' => [
                    [
                        'data' => array_values($kandangProduksi),
                        'backgroundColor' => array_slice($colors, 0, count($kandangProduksi)),
                    ],
                ],
            ],
            'table_data' => $tableData,
        ]);
    }
}
