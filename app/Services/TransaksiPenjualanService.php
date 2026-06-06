<?php

namespace App\Services;

use App\Models\AkunKas;
use App\Models\Barang;
use App\Models\BukuKas;
use App\Models\DetailPenjualan;
use App\Models\Kandang;
use App\Models\Penjualan;
use App\Models\Piutang;
use App\Models\RiwayatAktivitas;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TransaksiPenjualanService
{
    /**
     * Proses transaksi penjualan
     * 
     * @param array $data Data header penjualan
     * @param array $details Data rincian item (id_barang, qty, harga)
     * @return Penjualan
     * @throws \Exception
     */
    public function prosesTransaksi(array $data, array $details)
    {
        return DB::transaction(function () use ($data, $details) {
            $userId = Auth::id();
            
            // 1. Generate Nomor Faktur: PJ-YYYYMMDD-XX
            $tanggalKode = Carbon::today()->format('Ymd');
            $lastRecord = Penjualan::where('no_faktur_jual', 'like', "PJ-{$tanggalKode}-%")
                ->lockForUpdate()
                ->orderBy('no_faktur_jual', 'desc')
                ->first();

            $nextNumber = $lastRecord
                ? str_pad((int) substr($lastRecord->no_faktur_jual, -2) + 1, 2, '0', STR_PAD_LEFT)
                : '01';
            $noFaktur = "PJ-{$tanggalKode}-{$nextNumber}";

            // 2. Simpan Header Penjualan
            $penjualan = Penjualan::create([
                'no_faktur_jual' => $noFaktur,
                'id_pelanggan' => $data['id_pelanggan'],
                'id_pengguna' => $userId,
                'tanggal_penjualan' => Carbon::now(),
                'metode_pembayaran' => $data['metode_pembayaran'],
                'total_harga' => $data['total_harga'],
                'kategori_penjualan' => $data['kategori_penjualan'],
                'id_kandang' => $data['id_kandang'] ?? null,
                'catatan' => $data['catatan'] ?? null,
            ]);

            // 3. Simpan Detail & Kurangi Stok/Populasi
            $rincianText = [];
            foreach ($details as $item) {
                // Simpan baris detail
                DetailPenjualan::create([
                    'id_penjualan' => $penjualan->id_penjualan,
                    'id_barang' => $item['id_barang'],
                    'kuantitas' => $item['kuantitas'],
                    'harga_satuan' => $item['harga_satuan'],
                    'sub_total' => $item['sub_total'],
                ]);

                $barang = Barang::findOrFail($item['id_barang']);
                $rincianText[] = "{$barang->nama_barang} ({$item['kuantitas']})";

                if ($data['kategori_penjualan'] === 'afkir') {
                    // Logika Afkir: Kurangi populasi kandang target
                    if (empty($data['id_kandang'])) {
                        throw new \Exception("Kandang target wajib dipilih untuk penjualan ayam afkir.");
                    }
                    
                    $kandang = Kandang::lockForUpdate()->findOrFail($data['id_kandang']);
                    if ($kandang->populasi_saat_ini < $item['kuantitas']) {
                        throw new \Exception("Populasi kandang tidak mencukupi untuk penjualan ayam afkir. Tersedia: {$kandang->populasi_saat_ini}, Diminta: {$item['kuantitas']}");
                    }
                    $kandang->populasi_saat_ini -= $item['kuantitas'];
                    $kandang->save();
                    
                } else {
                    // Logika Telur & Pupuk: Kurangi stok gudang
                    $barangLok = Barang::lockForUpdate()->findOrFail($item['id_barang']);
                    if ($barangLok->stok_barang < $item['kuantitas']) {
                        throw new \Exception("Stok {$barangLok->nama_barang} tidak mencukupi. Tersedia: {$barangLok->stok_barang}, Diminta: {$item['kuantitas']}");
                    }
                    $barangLok->stok_barang -= $item['kuantitas'];
                    $barangLok->save();
                }
            }

            // 4. Logika Pembayaran (Lunas vs Piutang)
            if ($data['metode_pembayaran'] === 'LUNAS') {
                if (empty($data['id_akun_kas'])) {
                    throw new \Exception("Rekening tujuan wajib dipilih untuk pembayaran LUNAS.");
                }

                $akun = AkunKas::lockForUpdate()->findOrFail($data['id_akun_kas']);
                
                // Tambah saldo
                $akun->saldo_sekarang += $penjualan->total_harga;
                $akun->save();

                // Entry Buku Kas
                $lastJurnal = BukuKas::where('kode_jurnal', 'like', "BK-{$tanggalKode}-%")
                    ->orderBy('kode_jurnal', 'desc')
                    ->first();
                $nextJurnalNum = $lastJurnal
                    ? str_pad((int) substr($lastJurnal->kode_jurnal, -2) + 1, 2, '0', STR_PAD_LEFT)
                    : '01';

                BukuKas::create([
                    'kode_jurnal' => "BK-{$tanggalKode}-{$nextJurnalNum}",
                    'id_akun' => $akun->id_akun,
                    'id_pengguna' => $userId,
                    'tanggal_transaksi' => Carbon::now(),
                    'jenis' => 'Masuk',
                    'tipe_referensi' => 'penjualan',
                    'id_referensi' => $penjualan->id_penjualan,
                    'nominal' => $penjualan->total_harga,
                    'keterangan' => "Penjualan {$data['kategori_penjualan']} ({$noFaktur})",
                ]);

            } elseif ($data['metode_pembayaran'] === 'PIUTANG') {
                Piutang::create([
                    'id_penjualan' => $penjualan->id_penjualan,
                    'jumlah_piutang' => $penjualan->total_harga,
                    'sisa_piutang' => $penjualan->total_harga,
                    'status_piutang' => 'Belum Lunas',
                ]);
            } else {
                throw new \Exception("Metode pembayaran tidak valid.");
            }

            // 5. Catat Riwayat Aktivitas
            $itemSummary = implode(', ', $rincianText);
            RiwayatAktivitas::create([
                'id_pengguna' => $userId,
                'aktivitas' => "Mencatat transaksi penjualan {$data['kategori_penjualan']} ({$noFaktur}): {$itemSummary} senilai Rp" . number_format($penjualan->total_harga, 0, ',', '.'),
            ]);

            return $penjualan;
        });
    }
}
