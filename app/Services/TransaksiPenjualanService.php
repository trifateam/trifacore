<?php

namespace App\Services;

use App\Helpers\CodeGenerator;
use App\Models\AkunKas;
use App\Models\Barang;
use App\Models\BukuKas;
use App\Models\DetailPenjualan;
use App\Models\Penjualan;
use App\Models\Piutang;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TransaksiPenjualanService
{
    /**
     * Proses transaksi penjualan
     *
     * @param  array  $data  Data header penjualan
     * @param  array  $details  Data rincian item (id_barang, qty, harga)
     * @return Penjualan
     *
     * @throws \Exception
     */
    public function prosesTransaksi(array $data, array $details)
    {
        return DB::transaction(function () use ($data, $details) {
            $userId = Auth::id();

            // 1. Generate Nomor Faktur: PJ-YYYYMMDD-XX
            $noFaktur = CodeGenerator::generate('PJ', 'penjualan', 'no_faktur_jual');

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
                'status_order' => 'Menunggu',
            ]);

            // 3. Simpan Detail (stok akan dikurangi oleh Pegawai Gudang saat order selesai)
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
            }

            // 4. Logika Pembayaran (Lunas vs Piutang)
            if ($data['metode_pembayaran'] === 'LUNAS') {
                if (empty($data['id_akun_kas'])) {
                    throw new \Exception('Rekening tujuan wajib dipilih untuk pembayaran LUNAS.');
                }

                $akun = AkunKas::lockForUpdate()->findOrFail($data['id_akun_kas']);

                // Tambah saldo
                $akun->saldo += $penjualan->total_harga;
                $akun->save();

                // Entry Buku Kas
                $kodeJurnal = CodeGenerator::generate('BK', 'buku_kas', 'kode_jurnal', 4);

                BukuKas::create([
                    'kode_jurnal' => $kodeJurnal,
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
                    'tanggal_jatuh_tempo' => $data['tanggal_jatuh_tempo'],
                ]);
            } else {
                throw new \Exception('Metode pembayaran tidak valid.');
            }

            // 5. Catat Riwayat Aktivitas
            $itemSummary = implode(', ', $rincianText);
            AuditService::log("Mencatat transaksi penjualan {$data['kategori_penjualan']} ({$noFaktur}): {$itemSummary} senilai Rp".number_format($penjualan->total_harga, 0, ',', '.'));

            return $penjualan;
        });
    }
}
