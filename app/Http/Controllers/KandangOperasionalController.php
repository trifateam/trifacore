<?php

namespace App\Http\Controllers;

use App\Models\Batch;
use App\Models\Kandang;
use App\Services\AuditService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KandangOperasionalController extends Controller
{
    /**
     * Tampilkan halaman kandang operasional
     */
    public function index()
    {
        // Section 1: Kandang Aktif beserta batch-nya
        $kandangs = Kandang::with(['batches' => function ($query) {
            // Hanya batch yang aktif atau selesai di kandang tersebut
            $query->whereIn('status_batch', ['Aktif', 'Selesai']);
        }])
            ->whereNull('deleted_at')
            ->get();

        return view('kandang.index', compact('kandangs'));
    }

    public function performa()
    {
        $batches = Batch::with(['kandang', 'produksiTelur', 'deplesi'])
            ->where('status_batch', 'Aktif')
            ->get();

        $batchChartData = [];
        foreach ($batches as $batch) {
            // Group produksi by week number relative to tgl_masuk
            $weeklyHdp = [];
            $produksi = $batch->produksiTelur->sortBy('tanggal_produksi');

            foreach ($produksi as $p) {
                $daysSinceMasuk = Carbon::parse($batch->tgl_masuk)->diffInDays($p->tanggal_produksi);
                $weekNum = (int) floor($daysSinceMasuk / 7) + 1;

                if (! isset($weeklyHdp[$weekNum])) {
                    $weeklyHdp[$weekNum] = ['totalTelur' => 0, 'days' => 0, 'totalPopulasi' => 0];
                }

                $totalTelur = $p->jml_telur_rb + $p->jml_telur_mb + $p->jml_telur_mk + $p->jml_telur_pecah;

                // Populasi hari itu = populasi_awal - deplesi s/d tanggal
                $deplesiSd = $batch->deplesi
                    ->where('tanggal_deplesi', '<=', $p->tanggal_produksi)
                    ->sum(fn ($d) => $d->jml_mati + $d->jml_afkir);
                $populasiHariItu = max(1, $batch->populasi_awal - $deplesiSd);

                $weeklyHdp[$weekNum]['totalTelur'] += $totalTelur;
                $weeklyHdp[$weekNum]['totalPopulasi'] += $populasiHariItu;
                $weeklyHdp[$weekNum]['days']++;
            }

            // Compute HDP% per week
            $hdpData = [];
            $maxWeek = max(7, ! empty($weeklyHdp) ? max(array_keys($weeklyHdp)) : 7);

            for ($w = 1; $w <= $maxWeek; $w++) {
                if (isset($weeklyHdp[$w]) && $weeklyHdp[$w]['days'] > 0) {
                    $avgPopulasi = $weeklyHdp[$w]['totalPopulasi'] / $weeklyHdp[$w]['days'];
                    $totalTelurWeek = $weeklyHdp[$w]['totalTelur'];
                    $hdp = ($totalTelurWeek / ($avgPopulasi * $weeklyHdp[$w]['days'])) * 100;
                    $hdpData[$w] = round($hdp, 2);
                } else {
                    $hdpData[$w] = null; // No data for this week
                }
            }

            $batchChartData[] = [
                'batch' => $batch,
                'hdpData' => $hdpData,
                'sisaHariAfkir' => $batch->sisa_hari_afkir,
            ];
        }

        return view('batch.performa', compact('batchChartData'));
    }

    /**
     * Tampilkan data batch (Aktif saja)
     */
    public function batch()
    {
        $batches = Batch::with(['supplier', 'kandang'])
            ->where('status_batch', 'Aktif')
            ->orderBy('tgl_masuk', 'desc')
            ->get();

        return view('batch.index', compact('batches'));
    }

    /**
     * Tampilkan data batch masuk (Pending)
     */
    public function masuk()
    {
        $batches = Batch::with(['supplier'])
            ->where('status_batch', 'Pending')
            ->orderBy('tgl_masuk', 'desc')
            ->get();

        return view('batch.masuk', compact('batches'));
    }

    public function riwayat()
    {
        $batches = Batch::with('produksiTelur')
            ->where('status_batch', 'Selesai')
            ->orderBy('updated_at', 'desc')
            ->get()
            ->map(function ($batch) {
                $totalTelur = $batch->produksiTelur->sum(fn ($p) => $p->jml_telur_rb + $p->jml_telur_mb + $p->jml_telur_mk + $p->jml_telur_pecah
                );
                $batch->total_telur = $totalTelur;

                return $batch;
            });

        return view('batch.riwayat', compact('batches'));
    }

    public function showAssignForm($id_batch)
    {
        $batch = Batch::with('supplier')->findOrFail($id_batch);

        if ($batch->status_batch !== 'Pending') {
            return redirect()->route('batch.index')
                ->with('error', 'Batch ini sudah tidak dalam status Pending.');
        }

        // Fetch kandang beserta jumlah batch aktif di kandang tersebut
        $kandangs = Kandang::withCount(['batches as active_batch_count' => function ($query) {
            $query->where('status_batch', 'Aktif');
        }])->whereNull('deleted_at')->get();

        return view('batch.assign', compact('batch', 'kandangs'));
    }

    /**
     * Proses assignment pullet ke kandang
     */
    public function assign(Request $request, $id_batch)
    {
        $request->validate([
            'id_kandang' => 'required|exists:kandang,id_kandang',
        ]);

        try {
            DB::transaction(function () use ($request, $id_batch) {
                $batch = Batch::lockForUpdate()->findOrFail($id_batch);
                $kandang = Kandang::lockForUpdate()->findOrFail($request->id_kandang);

                if ($batch->status_batch !== 'Pending') {
                    throw new \Exception('Batch ini tidak dalam status Pending.');
                }

                // Validasi batas maksimal 2 batch aktif per kandang
                $activeBatchesCount = $kandang->batches()->where('status_batch', 'Aktif')->count();
                if ($activeBatchesCount >= 2) {
                    throw new \Exception('Kandang sudah mencapai batas maksimal 2 batch aktif.');
                }

                $jumlahAssign = $batch->populasi_saat_ini;

                // Assign semua sisa batch (All-in All-out)
                $batch->id_kandang = $kandang->id_kandang;
                $batch->status_batch = 'Aktif';
                $batch->nama_batch = $batch->kode_batch.' / '.$kandang->nama_kandang;
                $batch->save();

                // Update populasi kandang
                $kandang->populasi_saat_ini += $jumlahAssign;
                $kandang->save();

                // Catat aktivitas
                AuditService::log("Menempatkan keseluruhan {$jumlahAssign} ekor pullet (Batch: {$batch->kode_batch}) ke {$kandang->nama_kandang}.");
            });

            return redirect()->route('batch.index')
                ->with('success', 'Berhasil menempatkan pullet ke kandang.');

        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menempatkan pullet: '.$e->getMessage());
        }
    }
}
