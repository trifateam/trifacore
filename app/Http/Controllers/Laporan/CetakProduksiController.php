<?php

namespace App\Http\Controllers\Laporan;

use App\Http\Controllers\Controller;
use App\Models\Batch;
use App\Models\Deplesi;
use App\Models\Kandang;
use App\Models\ProduksiTelur;
use App\Models\Setting;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CetakProduksiController extends Controller
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

        return view('laporan.cetak.produksi-telur-filter', compact('kandangs', 'years'));
    }

    private function getReportData($kandangId, $bulan, $tahun)
    {
        $kandang = Kandang::findOrFail($kandangId);
        $settings = Setting::pluck('value', 'key')->toArray();

        $batches = Batch::where('id_kandang', $kandangId)->get();
        $batchIds = $batches->pluck('id_batch')->toArray();

        $startDate = Carbon::createFromDate($tahun, $bulan, 1)->startOfMonth();
        $endDate = $startDate->copy()->endOfMonth();

        $populasiAwalBulan = 0;
        foreach ($batches as $b) {
            $deplesiBefore = Deplesi::where('id_batch', $b->id_batch)
                ->where('tanggal_deplesi', '<', $startDate->toDateString())
                ->sum(DB::raw('jml_mati + jml_cacat'));

            $populasi = $b->populasi_awal - $deplesiBefore;
            if ($populasi > 0) {
                $populasiAwalBulan += $populasi;
            }
        }

        $produksiData = ProduksiTelur::whereIn('id_batch', $batchIds)
            ->whereMonth('tanggal_produksi', $bulan)
            ->whereYear('tanggal_produksi', $tahun)
            ->get();

        $deplesiData = Deplesi::whereIn('id_batch', $batchIds)
            ->whereMonth('tanggal_deplesi', $bulan)
            ->whereYear('tanggal_deplesi', $tahun)
            ->get();

        $dailyData = [];
        $daysInMonth = $endDate->daysInMonth;

        $totalProduksiBulan = 0;
        $totalMortalitasBulan = 0;
        $totalHDP = 0;
        $hdpCount = 0;

        $currentPopulation = $populasiAwalBulan;

        // Ensure we iterate up to today if the month is the current month
        // Wait, the prompt says "per hari untuk kandang+bulan terpilih"
        // Let's just output days where there is data, or all days up to endOfMonth
        for ($day = 1; $day <= $daysInMonth; $day++) {
            $dateStr = Carbon::createFromDate($tahun, $bulan, $day)->toDateString();

            // To properly filter collections by date
            $prodToday = $produksiData->filter(function ($item) use ($dateStr) {
                return $item->tanggal_produksi->format('Y-m-d') == $dateStr;
            });

            $depToday = $deplesiData->filter(function ($item) use ($dateStr) {
                return $item->tanggal_deplesi->format('Y-m-d') == $dateStr;
            });

            $rb = $prodToday->sum('jml_telur_rb');
            $mb = $prodToday->sum('jml_telur_mb');
            $mk = $prodToday->sum('jml_telur_mk');
            $pecah = $prodToday->sum('jml_telur_pecah');
            $totalTelur = $rb + $mb + $mk + $pecah;

            $mati = $depToday->sum('jml_mati');
            $afkir = $depToday->sum('jml_cacat');
            $totalMortalitasHari = $mati + $afkir;

            $hdp = 0;
            if ($currentPopulation > 0) {
                $hdp = ($totalTelur / $currentPopulation) * 100;
            }

            // Always add to dailyData so the table looks complete or just the days with data?
            // A production report usually shows every day of the month.
            $dailyData[] = [
                'tanggal' => Carbon::parse($dateStr)->format('d-m-Y'),
                'rb' => $rb,
                'mb' => $mb,
                'mk' => $mk,
                'pecah' => $pecah,
                'total_telur' => $totalTelur,
                'mati' => $mati,
                'afkir' => $afkir,
                'populasi' => $currentPopulation,
                'hdp' => round($hdp, 2),
            ];

            if ($totalTelur > 0) {
                $totalHDP += $hdp;
                $hdpCount++;
            }

            $totalProduksiBulan += $totalTelur;
            $totalMortalitasBulan += $totalMortalitasHari;
            $currentPopulation -= $totalMortalitasHari;
        }

        $rataHDP = $hdpCount > 0 ? round($totalHDP / $hdpCount, 2) : 0;
        $populasiAkhirBulan = $currentPopulation;

        return compact(
            'kandang', 'bulan', 'tahun', 'settings',
            'dailyData', 'totalProduksiBulan', 'totalMortalitasBulan',
            'populasiAwalBulan', 'populasiAkhirBulan', 'rataHDP'
        );
    }

    public function preview(Request $request)
    {
        $request->validate([
            'kandang_id' => 'required',
            'bulan' => 'required',
            'tahun' => 'required',
        ]);

        $data = $this->getReportData($request->kandang_id, $request->bulan, $request->tahun);

        return view('laporan.cetak.produksi-telur', $data);
    }

    public function pdf(Request $request)
    {
        $request->validate([
            'kandang_id' => 'required',
            'bulan' => 'required',
            'tahun' => 'required',
        ]);

        $data = $this->getReportData($request->kandang_id, $request->bulan, $request->tahun);

        $pdf = Pdf::loadView('laporan.cetak.produksi-telur', $data)
            ->setPaper('a4', 'landscape');

        $filename = 'Laporan-Produksi-'.str_replace(' ', '-', $data['kandang']->nama_kandang).'-'.$request->bulan.'-'.$request->tahun.'.pdf';

        return $pdf->download($filename);
    }
}
