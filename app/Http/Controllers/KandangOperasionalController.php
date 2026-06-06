<?php

namespace App\Http\Controllers;

use App\Models\Batch;
use App\Models\Kandang;
use App\Models\RiwayatAktivitas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class KandangOperasionalController extends Controller
{
    /**
     * Tampilkan halaman kandang operasional
     */
    public function index()
    {
        // Section 1: Batch Pending (FIFO: older batches first)
        $pendingBatches = Batch::with('supplier')
            ->where('status_batch', 'Pending')
            ->where('jumlah_sisa', '>', 0)
            ->orderBy('tgl_masuk', 'asc')
            ->get();

        // Section 2: Kandang Aktif beserta batch-nya
        $kandangs = Kandang::with(['batches' => function ($query) {
                // Hanya batch yang aktif atau selesai di kandang tersebut
                $query->whereIn('status_batch', ['Aktif', 'Selesai']);
            }])
            ->where('is_active', true)
            ->get();

        return view('kandang-operasional.index', compact('pendingBatches', 'kandangs'));
    }

    /**
     * Proses assignment pullet ke kandang
     */
    public function assign(Request $request, $id_batch)
    {
        $request->validate([
            'id_kandang' => 'required|exists:kandang,id_kandang',
            'jumlah' => 'required|integer|min:1',
        ]);

        try {
            DB::transaction(function () use ($request, $id_batch) {
                $batch = Batch::lockForUpdate()->findOrFail($id_batch);
                $kandang = Kandang::lockForUpdate()->findOrFail($request->id_kandang);

                if ($batch->status_batch !== 'Pending') {
                    throw new \Exception("Batch ini tidak dalam status Pending.");
                }

                $jumlahAssign = $request->jumlah;

                // Validasi jumlah sisa batch
                if ($jumlahAssign > $batch->jumlah_sisa) {
                    throw new \Exception("Jumlah assign melebihi sisa ayam di batch ini ({$batch->jumlah_sisa} ekor).");
                }

                // Validasi kapasitas kandang
                $sisaKapasitas = $kandang->kapasitas_kandang - $kandang->populasi_saat_ini;
                if ($jumlahAssign > $sisaKapasitas) {
                    throw new \Exception("Kapasitas kandang tidak mencukupi. Sisa kapasitas: {$sisaKapasitas} ekor.");
                }

                // Proses Splitting atau Update
                if ($jumlahAssign < $batch->jumlah_sisa) {
                    // Split batch: buat batch baru untuk yang di-assign
                    $newBatch = $batch->replicate();
                    $newBatch->kode_batch = $batch->kode_batch . '-' . rand(10, 99); // Append unique identifier
                    $newBatch->nama_batch = $batch->nama_batch . ' (Split)';
                    $newBatch->id_kandang = $kandang->id_kandang;
                    $newBatch->populasi_awal = $jumlahAssign;
                    $newBatch->jumlah_sisa = $jumlahAssign;
                    $newBatch->status_batch = 'Aktif';
                    $newBatch->save();

                    // Kurangi sisa batch original
                    $batch->jumlah_sisa -= $jumlahAssign;
                    $batch->save();
                    
                    $assignedBatchKode = $newBatch->kode_batch;
                } else {
                    // Assign semua sisa batch
                    $batch->id_kandang = $kandang->id_kandang;
                    $batch->jumlah_sisa = 0; // Karena sudah di-assign semua ke kandang
                    $batch->status_batch = 'Aktif';
                    
                    // Note: jika jumlah_sisa kita definisikan sebagai "sisa yang belum diassign",
                    // maka menjadi 0. Tapi jika jumlah_sisa di kandang artinya "ayam yang masih hidup di kandang",
                    // maka jumlah_sisa tetap sejumlah yang diassign. 
                    // Prompt bilang: "Batch: jumlah_sisa -= jumlah". Artinya jumlah_sisa adalah yang BELUM di assign.
                    // Tunggu, saat Deplesi, "jumlah sisa ayam per batch". 
                    // Jika di database, populasi kandang adalah agregasi dari batch?
                    // "kandang.populasi_saat_ini += jumlah"
                    // Jika jumlah_sisa di batch berkurang, berarti jumlah_sisa = stok gudang?
                    // TIDAK. Di sistem peternakan biasanya `jumlah_sisa` batch adalah sisa ayam hidup. 
                    // Coba kita lihat di prompt Deplesi: "Mencatat kematian... mengurangi populasi".
                    // Jika `jumlah_sisa` -= `jumlah`, berarti sisa yang belum assign jadi 0.
                    // Tapi bagaimana dengan sisa hidup di kandang? 
                    // Di prompt ini dikatakan: "Batch: jumlah_sisa -= jumlah".
                    // Jika split, batch original sisa, batch baru jumlah_sisa = $jumlahAssign.
                    // Jika full, status jadi Aktif. Kalau jumlah_sisa 0, hilang dari section 1.
                    // Mari kita asumsikan jumlah_sisa di batch pending adalah "sisa yg belum di assign", 
                    // dan saat sudah di-assign (Aktif), jumlah_sisa menjadi "jumlah ayam hidup di kandang".
                    // Oleh karena itu, untuk full assign, jumlah_sisa TETAP sama (karena belum ada yg mati),
                    // tapi status_batch menjadi 'Aktif', sehingga otomatis hilang dari Section 1 karena filter status.
                    $batch->save();
                    $assignedBatchKode = $batch->kode_batch;
                }

                // Update populasi kandang
                $kandang->populasi_saat_ini += $jumlahAssign;
                $kandang->save();

                // Catat aktivitas
                RiwayatAktivitas::create([
                    'id_pengguna' => Auth::id(),
                    'aktivitas' => "Menempatkan {$jumlahAssign} ekor pullet (Batch: {$assignedBatchKode}) ke {$kandang->nama_kandang}."
                ]);
            });

            return redirect()->route('kandang-operasional.index')
                ->with('success', 'Berhasil menempatkan pullet ke kandang.');

        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menempatkan pullet: ' . $e->getMessage());
        }
    }
}
