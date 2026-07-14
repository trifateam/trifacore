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
                    ->sum(fn ($d) => $d->jml_mati + $d->jml_cacat);
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
    public function afkir(Request $request, $id_batch)
    {
        $request->validate([
            'jumlah_afkir' => 'required|integer|min:1',
        ]);

        try {
            DB::transaction(function () use ($request, $id_batch) {
                $batch = Batch::lockForUpdate()->findOrFail($id_batch);
                
                if (!$batch->id_kandang) {
                    throw new \Exception('Batch tidak memiliki kandang aktif.');
                }
                
                $kandang = Kandang::lockForUpdate()->findOrFail($batch->id_kandang);

                if ($batch->status_batch !== 'Aktif') {
                    throw new \Exception('Hanya batch aktif yang dapat diafkirkan.');
                }

                $jumlahAfkir = $request->jumlah_afkir;

                if ($jumlahAfkir > $batch->populasi_saat_ini) {
                    throw new \Exception('Jumlah afkir melebihi populasi batch saat ini.');
                }

                // Update populasi batch dan kandang
                $batch->populasi_saat_ini -= $jumlahAfkir;
                $kandang->populasi_saat_ini -= $jumlahAfkir;

                if ($batch->populasi_saat_ini <= 0) {
                    $batch->status_batch = 'Selesai';
                    $batch->tgl_afkir = now();
                    $batch->populasi_saat_ini = 0;
                }

                $batch->save();
                $kandang->save();

                // Cari atau buat produk Ayam Afkir untuk batch ini
                $namaProduk = "Ayam Afkir - " . $batch->nama_batch;
                $barang = \App\Models\Barang::firstOrCreate(
                    [
                        'nama_barang' => $namaProduk,
                        'kategori_barang' => 'Ayam',
                    ],
                    [
                        'id_pengguna' => auth()->id() ?? 1,
                        'satuan' => 'Ekor',
                        'stok_barang' => 0,
                        'stok_minimum' => 0,
                        'harga' => 0,
                        'dapat_dijual' => true,
                        'dapat_dibeli' => false,
                    ]
                );

                // Tambah stok ke gudang
                $barang->stok_barang += $jumlahAfkir;
                $barang->save();

                // Catat di log penyesuaian stok
                \App\Models\LogPenyesuaianStok::create([
                    'id_barang' => $barang->id_barang,
                    'id_pengguna' => auth()->id() ?? 1,
                    'stok_lama' => $barang->stok_barang - $jumlahAfkir,
                    'stok_baru' => $barang->stok_barang,
                    'alasan' => "Afkir Ayam dari Kandang {$kandang->nama_kandang} (Batch: {$batch->kode_batch})",
                ]);

                // Catat aktivitas
                AuditService::log("Mengafkirkan {$jumlahAfkir} ekor ayam dari batch {$batch->nama_batch} dan memindahkannya ke stok gudang.");
            });

            return redirect()->route('batch.index')
                ->with('success', 'Berhasil mengafkirkan ayam dan memindahkannya ke stok gudang.');

        } catch (\Exception $e) {
            return back()->with('error', 'Gagal mengafkirkan ayam: ' . $e->getMessage());
        }
    }
}
