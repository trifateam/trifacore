<?php

namespace App\Http\Controllers\Keuangan;

use App\Helpers\CodeGenerator;
use App\Http\Controllers\Controller;
use App\Models\AkunKas;
use App\Models\BukuKas;
use App\Models\Pelanggan;
use App\Models\PembayaranPiutang;
use App\Models\Piutang;
use App\Services\AuditService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BukuPiutangController extends Controller
{
    /**
     * Tampilkan daftar piutang dengan filter dan summary.
     */
    public function index(Request $request)
    {
        // Ambil semua pelanggan untuk dropdown filter
        $pelanggans = Pelanggan::orderBy('nama_lengkap')->get();

        // Akun kas aktif untuk modal pelunasan
        $akunKas = AkunKas::all();

        // Base query — join penjualan + pelanggan
        $query = Piutang::with(['penjualan.pelanggan']);

        // ── Filter: Pelanggan ──
        if ($request->filled('id_pelanggan')) {
            $query->whereHas('penjualan', function ($q) use ($request) {
                $q->where('id_pelanggan', $request->id_pelanggan);
            });
        }

        // ── Filter: Status ──
        if ($request->filled('status') && in_array($request->status, ['Belum Lunas', 'Lunas Sebagian', 'Lunas'])) {
            $query->where('status_piutang', $request->status);
        }

        // ── Summary Cards ──
        $totalPiutangBelumLunas = Piutang::where('status_piutang', '!=', 'Lunas')->sum('sisa_piutang');
        $jumlahFakturTempo = Piutang::where('status_piutang', '!=', 'Lunas')->count();

        // ── Data Tabel + Pagination ──
        $piutangs = $query->orderByRaw("
            CASE 
                WHEN status_piutang = 'Lunas' THEN 1 
                ELSE 0 
            END ASC
        ")
            ->orderBy('tanggal_jatuh_tempo', 'asc')
            ->paginate(15)
            ->withQueryString();

        return view('keuangan.buku-piutang.index', compact(
            'pelanggans',
            'akunKas',
            'piutangs',
            'totalPiutangBelumLunas',
            'jumlahFakturTempo',
        ));
    }

    /**
     * Tampilkan form pelunasan piutang di halaman penuh.
     */
    public function showLunasiForm($piutang)
    {
        $piutang = Piutang::with('penjualan.pelanggan')->findOrFail($piutang);

        if ($piutang->status_piutang === 'Lunas') {
            return redirect()->route('keuangan.buku-piutang')->with('error', 'Piutang ini sudah lunas.');
        }

        $akunKas = AkunKas::all();

        return view('keuangan.buku-piutang.lunasi', compact('piutang', 'akunKas'));
    }

    /**
     * Proses pelunasan piutang (partial atau full).
     * Uang MASUK ke kas (saldo bertambah).
     */
    public function lunasi(Request $request, $piutang)
    {
        $request->validate([
            'nominal' => 'required|numeric|min:1',
            'id_akun' => 'required|exists:akun_kas,id_akun',
        ]);

        $piutang = Piutang::with('penjualan.pelanggan')->findOrFail($piutang);

        // Validasi nominal <= sisa piutang
        if ($request->nominal > $piutang->sisa_piutang) {
            return back()->with('error', 'Nominal pelunasan melebihi sisa piutang (Rp '.number_format($piutang->sisa_piutang, 0, ',', '.').').');
        }

        try {
            DB::transaction(function () use ($request, $piutang) {
                $nominal = $request->nominal;

                // Generate no_kuitansi_piutang: BPI-YYYYMMDD-XX
                $noKuitansi = CodeGenerator::generate('BPI', 'pembayaran_piutang', 'no_kuitansi_piutang');

                // Simpan ke pembayaran_piutang
                $pembayaran = PembayaranPiutang::create([
                    'no_kuitansi_piutang' => $noKuitansi,
                    'id_piutang' => $piutang->id_piutang,
                    'id_pengguna' => Auth::id(),
                    'id_akun' => $request->id_akun,
                    'tanggal_pembayaran' => now(),
                    'jumlah_bayar' => $nominal,
                    'keterangan' => 'Pelunasan piutang nota '.$piutang->penjualan->no_faktur_jual,
                ]);

                // Update sisa_piutang
                $piutang->sisa_piutang -= $nominal;

                // Update status_piutang
                if ($piutang->sisa_piutang <= 0) {
                    $piutang->sisa_piutang = 0;
                    $piutang->status_piutang = 'Lunas';
                    $piutang->tanggal_pelunasan = now();
                } else {
                    $piutang->status_piutang = 'Lunas Sebagian';
                }
                $piutang->save();

                // TAMBAH saldo akun_kas (uang MASUK)
                $akunLock = AkunKas::lockForUpdate()->findOrFail($request->id_akun);
                $akunLock->saldo += $nominal;
                $akunLock->save();

                // Buat entry buku_kas (jenis='Masuk')
                $kodeJurnal = CodeGenerator::generate('JRN', 'buku_kas', 'kode_jurnal', 4);

                BukuKas::create([
                    'kode_jurnal' => $kodeJurnal,
                    'id_akun' => $request->id_akun,
                    'id_pengguna' => Auth::id(),
                    'tanggal_transaksi' => now(),
                    'jenis' => 'Masuk',
                    'tipe_referensi' => 'pembayaran_piutang',
                    'id_referensi' => $pembayaran->id_pembayaran_piutang,
                    'nominal' => $nominal,
                    'keterangan' => 'Pelunasan piutang '.($piutang->penjualan->pelanggan->nama_lengkap ?? '').' - Nota '.$piutang->penjualan->no_faktur_jual,
                ]);

                // Catat riwayat aktivitas
                $pelangganName = $piutang->penjualan->pelanggan->nama_lengkap ?? 'Unknown';
                AuditService::log("Menerima pelunasan piutang {$pelangganName} (Nota: {$piutang->penjualan->no_faktur_jual}) sebesar Rp".number_format($nominal, 0, ',', '.'));
            });

            return redirect()->route('keuangan.buku-piutang')->with('success', 'Pelunasan piutang berhasil diproses.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memproses pelunasan: '.$e->getMessage());
        }
    }
}
