<?php

namespace App\Http\Controllers\Keuangan;

use App\Helpers\CodeGenerator;
use App\Http\Controllers\Controller;
use App\Models\AkunKas;
use App\Models\BukuKas;
use App\Models\Hutang;
use App\Models\PembayaranHutang;
use App\Models\Supplier;
use App\Services\AuditService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BukuUtangController extends Controller
{
    /**
     * Tampilkan daftar utang dengan filter dan summary.
     */
    public function index(Request $request)
    {
        // Ambil semua supplier untuk dropdown filter
        $suppliers = Supplier::orderBy('nama_supplier')->get();

        // Akun kas aktif untuk modal pelunasan
        $akunKas = AkunKas::all();

        // Base query — join pembelian + supplier
        $query = Hutang::with(['pembelian.supplier']);

        // ── Filter: Supplier ──
        if ($request->filled('id_supplier')) {
            $query->whereHas('pembelian', function ($q) use ($request) {
                $q->where('id_supplier', $request->id_supplier);
            });
        }

        // ── Filter: Type (Menu Sub-Menu) ──
        if ($request->filled('type')) {
            if ($request->type === 'aktif') {
                $query->where('status_hutang', '!=', 'Lunas');
            } elseif ($request->type === 'riwayat') {
                $query->where('status_hutang', '=', 'Lunas');
            }
        }

        // ── Filter: Status ──
        if ($request->filled('status') && in_array($request->status, ['Belum Lunas', 'Lunas Sebagian', 'Lunas'])) {
            $query->where('status_hutang', $request->status);
        }

        // ── Summary Cards ──
        $totalUtangBelumLunas = Hutang::where('status_hutang', '!=', 'Lunas')->sum('sisa_hutang');
        $jumlahFakturTempo = Hutang::where('status_hutang', '!=', 'Lunas')->count();

        // ── Data Tabel + Pagination ──
        $hutangs = $query->orderByRaw("
            CASE 
                WHEN status_hutang = 'Lunas' THEN 1 
                ELSE 0 
            END ASC
        ")
            ->orderBy('tanggal_jatuh_tempo', 'asc')
            ->paginate(15)
            ->withQueryString();

        return view('keuangan.buku-utang.index', compact(
            'suppliers',
            'akunKas',
            'hutangs',
            'totalUtangBelumLunas',
            'jumlahFakturTempo',
        ));
    }

    /**
     * Tampilkan form pelunasan utang di halaman penuh.
     */
    public function showLunasiForm($hutang)
    {
        $hutang = Hutang::with('pembelian.supplier')->findOrFail($hutang);

        if ($hutang->status_hutang === 'Lunas') {
            return redirect()->route('keuangan.buku-utang')->with('error', 'Utang ini sudah lunas.');
        }

        $akunKas = AkunKas::all();

        return view('keuangan.buku-utang.lunasi', compact('hutang', 'akunKas'));
    }

    /**
     * Proses pelunasan utang (partial atau full).
     */
    public function lunasi(Request $request, $hutang)
    {
        $request->validate([
            'nominal' => 'required|numeric|min:1',
            'id_akun' => 'required|exists:akun_kas,id_akun',
        ]);

        $hutang = Hutang::with('pembelian.supplier')->findOrFail($hutang);

        // Validasi nominal <= sisa utang
        if ($request->nominal > $hutang->sisa_hutang) {
            return back()->with('error', 'Nominal pelunasan melebihi sisa utang (Rp '.number_format($hutang->sisa_hutang, 0, ',', '.').').');
        }

        // Validasi saldo rekening
        $akun = AkunKas::findOrFail($request->id_akun);
        if ($akun->saldo < $request->nominal) {
            return back()->with('error', 'Saldo rekening '.$akun->nama_akun.' tidak mencukupi (Saldo: Rp '.number_format($akun->saldo, 0, ',', '.').').');
        }

        try {
            DB::transaction(function () use ($request, $hutang, $akun) {
                $nominal = $request->nominal;

                // Generate no_kuitansi_hutang: BHU-YYYYMMDD-XX
                $noKuitansi = CodeGenerator::generate('BHU', 'pembayaran_hutang', 'no_kuitansi_hutang');

                // Simpan ke pembayaran_hutang
                $pembayaran = PembayaranHutang::create([
                    'no_kuitansi_hutang' => $noKuitansi,
                    'id_hutang' => $hutang->id_hutang,
                    'id_pengguna' => Auth::id(),
                    'id_akun' => $akun->id_akun,
                    'tanggal_pembayaran' => now(),
                    'jumlah_bayar' => $nominal,
                    'keterangan' => 'Pelunasan utang nota '.$hutang->pembelian->no_faktur_beli,
                ]);

                // Update sisa_hutang
                $hutang->sisa_hutang -= $nominal;

                // Update status_hutang
                if ($hutang->sisa_hutang <= 0) {
                    $hutang->sisa_hutang = 0;
                    $hutang->status_hutang = 'Lunas';
                    $hutang->tanggal_pelunasan = now();
                } else {
                    $hutang->status_hutang = 'Lunas Sebagian';
                }
                $hutang->save();

                // Kurangi saldo akun_kas
                $akunLock = AkunKas::lockForUpdate()->findOrFail($akun->id_akun);
                $akunLock->saldo -= $nominal;
                $akunLock->save();

                // Buat entry buku_kas
                $kodeJurnal = CodeGenerator::generate('JRN', 'buku_kas', 'kode_jurnal', 4);

                BukuKas::create([
                    'kode_jurnal' => $kodeJurnal,
                    'id_akun' => $akun->id_akun,
                    'id_pengguna' => Auth::id(),
                    'tanggal_transaksi' => now(),
                    'jenis' => 'Keluar',
                    'tipe_referensi' => 'pembayaran_hutang',
                    'id_referensi' => $pembayaran->id_pembayaran_hutang,
                    'nominal' => $nominal,
                    'keterangan' => 'Pelunasan utang '.($hutang->pembelian->supplier->nama_supplier ?? '').' - Nota '.$hutang->pembelian->no_faktur_beli,
                ]);

                // Catat riwayat aktivitas
                $supplierName = $hutang->pembelian->supplier->nama_supplier ?? 'Unknown';
                AuditService::log("Melunasi utang {$supplierName} (Nota: {$hutang->pembelian->no_faktur_beli}) sebesar Rp".number_format($nominal, 0, ',', '.'));
            });

            return redirect()->route('keuangan.buku-utang')->with('success', 'Pelunasan utang berhasil diproses.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memproses pelunasan: '.$e->getMessage());
        }
    }
}
