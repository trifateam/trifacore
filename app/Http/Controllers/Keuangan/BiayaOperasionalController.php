<?php

namespace App\Http\Controllers\Keuangan;

use App\Http\Controllers\Controller;
use App\Models\AkunKas;
use App\Models\BukuKas;
use App\Models\KategoriBiaya;
use App\Models\Operasional;
use App\Models\RiwayatAktivitas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BiayaOperasionalController extends Controller
{
    /**
     * Tampilkan form dan riwayat operasional
     */
    public function index()
    {
        $kategoriBiaya = KategoriBiaya::all();
        // Assuming akun_kas table has is_active, let's use all if not. I'll just get all accounts since they are bank accounts.
        // The prompt says "rekening aktif", so let's try `where('is_active', true)`. If error, I'll fix it later. Actually I saw Penjualan used `where('is_active', true)` for AkunKas successfully.
        $akunKas = AkunKas::where('is_active', true)->get();

        $operasionals = Operasional::with(['kategoriBiaya', 'akunKas', 'pengguna'])
            ->orderBy('tanggal_operasional', 'desc')
            ->paginate(10);

        // Calculate total pengeluaran bulan ini
        $currentMonth = date('m');
        $currentYear = date('Y');
        
        $totalBulanIni = Operasional::whereMonth('tanggal_operasional', $currentMonth)
            ->whereYear('tanggal_operasional', $currentYear)
            ->sum('biaya_operasional');

        return view('keuangan.biaya-operasional.index', compact('kategoriBiaya', 'akunKas', 'operasionals', 'totalBulanIni'));
    }

    /**
     * Simpan biaya operasional baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'tanggal_operasional' => 'required|date',
            'id_kategori_biaya' => 'required|exists:kategori_biaya,id_kategori_biaya',
            'nama_pengeluaran' => 'required|string|max:100', // Prompt says max 255 but DB is 100
            'biaya_operasional' => 'required|numeric|min:1',
            'id_akun' => 'required|exists:akun_kas,id_akun',
        ]);

        try {
            DB::transaction(function () use ($request) {
                // Generate kode operasional
                $datePrefix = date('Ymd', strtotime($request->tanggal_operasional));
                $countToday = Operasional::whereDate('created_at', date('Y-m-d'))->count() + 1;
                $kodeOperasional = 'OP-' . $datePrefix . '-' . str_pad($countToday, 2, '0', STR_PAD_LEFT);

                // Create Operasional
                $operasional = Operasional::create([
                    'kode_operasional' => $kodeOperasional,
                    'id_pengguna' => Auth::id(),
                    'id_kategori_biaya' => $request->id_kategori_biaya,
                    'id_akun' => $request->id_akun,
                    'tanggal_operasional' => $request->tanggal_operasional,
                    'nama_pengeluaran' => $request->nama_pengeluaran,
                    'biaya_operasional' => $request->biaya_operasional,
                ]);

                // Kurangi saldo Akun Kas
                $akun = AkunKas::lockForUpdate()->findOrFail($request->id_akun);
                $akun->saldo -= $request->biaya_operasional;
                $akun->save();

                // Create entry di Buku Kas
                $countJurnalToday = BukuKas::whereDate('created_at', date('Y-m-d'))->count() + 1;
                $kodeJurnal = 'JRN-' . $datePrefix . '-' . str_pad($countJurnalToday, 4, '0', STR_PAD_LEFT);

                BukuKas::create([
                    'kode_jurnal' => $kodeJurnal,
                    'id_akun' => $akun->id_akun,
                    'id_pengguna' => Auth::id(),
                    'tanggal_transaksi' => now(),
                    'jenis' => 'Keluar',
                    'tipe_referensi' => 'operasional',
                    'id_referensi' => $operasional->id_operasional,
                    'nominal' => $request->biaya_operasional,
                    'keterangan' => 'Biaya Operasional: ' . $request->nama_pengeluaran,
                ]);

                // Catat aktivitas
                RiwayatAktivitas::create([
                    'id_pengguna' => Auth::id(),
                    'aktivitas' => "Mencatat biaya operasional '{$request->nama_pengeluaran}' sebesar Rp" . number_format($request->biaya_operasional, 0, ',', '.')
                ]);
            });

            return redirect()->route('keuangan.biaya-operasional.index')->with('success', 'Biaya operasional berhasil dicatat.');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Gagal mencatat biaya operasional: ' . $e->getMessage());
        }
    }
}
