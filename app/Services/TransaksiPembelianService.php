<?php

namespace App\Services;

use App\Models\AkunKas;
use App\Models\Barang;
use App\Models\Batch;
use App\Models\BukuKas;
use App\Models\DetailPembelian;
use App\Models\Hutang;
use App\Models\Pembelian;
use App\Models\RiwayatAktivitas;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TransaksiPembelianService
{
    /**
     * Proses transaksi pembelian material gudang
     * 
     * @param array $data Data header pembelian
     * @param array $details Data rincian item
     * @return Pembelian
     * @throws \Exception
     */
    public function prosesBeliMaterial(array $data, array $details)
    {
        return DB::transaction(function () use ($data, $details) {
            $userId = Auth::id();
            
            // 1. Generate Nomor Faktur: PB-YYYYMMDD-XX
            $noFaktur = $this->generateNoFakturBeli();

            // 2. Simpan Header Pembelian
            $pembelian = Pembelian::create([
                'no_faktur_beli' => $noFaktur,
                'id_supplier' => $data['id_supplier'],
                'id_pengguna' => $userId,
                'tanggal_pembelian' => Carbon::now(),
                'metode_pembayaran' => $data['metode_pembayaran'],
                'total_pembelian' => $data['total_pembelian'],
                'kategori_pembelian' => 'Material',
                'catatan' => $data['catatan'] ?? null,
            ]);

            // 3. Simpan Detail & Tambah Stok
            $rincianText = [];
            foreach ($details as $item) {
                // Simpan baris detail
                DetailPembelian::create([
                    'id_pembelian' => $pembelian->id_pembelian,
                    'id_barang' => $item['id_barang'],
                    'kuantitas' => $item['kuantitas'],
                    'harga_satuan' => $item['harga_satuan'],
                    'subtotal' => $item['sub_total'],
                ]);

                // Tambah stok dan update harga
                $barang = Barang::lockForUpdate()->findOrFail($item['id_barang']);
                $barang->stok_barang += $item['kuantitas'];
                $barang->harga = $item['harga_satuan']; // Update harga terakhir
                $barang->save();

                $rincianText[] = "{$barang->nama_barang} ({$item['kuantitas']})";
            }

            // 4. Logika Pembayaran (Lunas vs Tempo)
            $this->prosesPembayaran($pembelian, $data['metode_pembayaran'], $data['id_akun_kas'] ?? null, $userId);

            // 5. Catat Riwayat Aktivitas
            $itemSummary = implode(', ', $rincianText);
            RiwayatAktivitas::create([
                'id_pengguna' => $userId,
                'aktivitas' => "Mencatat pembelian Material ({$noFaktur}): {$itemSummary} senilai Rp" . number_format($pembelian->total_pembelian, 0, ',', '.'),
            ]);

            return $pembelian;
        });
    }

    /**
     * Proses transaksi pembelian pullet (DOC / Ayam Muda)
     */
    public function prosesBeliPullet(array $data)
    {
        return DB::transaction(function () use ($data) {
            $userId = Auth::id();
            
            // 1. Generate Nomor Faktur
            $noFaktur = $this->generateNoFakturBeli();

            // 2. Simpan Header Pembelian
            $pembelian = Pembelian::create([
                'no_faktur_beli' => $noFaktur,
                'id_supplier' => $data['id_supplier'],
                'id_pengguna' => $userId,
                'tanggal_pembelian' => Carbon::now(),
                'metode_pembayaran' => $data['metode_pembayaran'],
                'total_pembelian' => $data['total_pembelian'],
                'kategori_pembelian' => 'Pullet',
                'catatan' => $data['catatan'] ?? null,
            ]);

            // 3. Simpan Detail Pembelian (1 Baris Dummy/Ayam)
            // Cari barang kategori Ayam atau yang ada kata Ayam/Pullet
            $barangAyam = Barang::where('kategori_barang', 'Ayam')
                                ->orWhere('nama_barang', 'like', '%Ayam%')
                                ->orWhere('nama_barang', 'like', '%Pullet%')
                                ->first();
                                
            if (!$barangAyam) {
                throw new \Exception("Master data untuk kategori Ayam tidak ditemukan. Harap buat barang dengan kategori 'Ayam' terlebih dahulu.");
            }

            DetailPembelian::create([
                'id_pembelian' => $pembelian->id_pembelian,
                'id_barang' => $barangAyam->id_barang,
                'kuantitas' => $data['jumlah_awal'],
                'harga_satuan' => $data['harga_per_ekor'],
                'subtotal' => $data['total_pembelian'],
            ]);

            // 4. BUAT BATCH BARU
            $tanggalBeli = Carbon::now();
            $tanggalFormat = $tanggalBeli->format('Ymd');
            
            // Generate Kode Batch: BTC-YYYYMMDD-XX
            $kodeBatch = \App\Helpers\CodeGenerator::generate('BTC', 'batch', 'kode_batch');
            
            $namaBatch = "Batch {$data['jenis_ayam']} " . $tanggalBeli->translatedFormat('d M Y');

            Batch::create([
                'kode_batch' => $kodeBatch,
                'id_kandang' => null, // Belum di assign
                'nama_batch' => $namaBatch,
                'jenis_ayam' => $data['jenis_ayam'],
                'tgl_masuk' => $tanggalBeli->toDateString(),
                'umur_awal_minggu' => $data['umur_masuk'],
                'populasi_awal' => $data['jumlah_awal'],
                'jumlah_sisa' => $data['jumlah_awal'], // Sama dengan awal karena belum mati/afkir/dimasukkan kandang
                'status_batch' => 'Pending',
                'id_supplier' => $data['id_supplier'],
                'harga_per_ekor' => $data['harga_per_ekor'],
            ]);

            // 5. Logika Pembayaran
            $this->prosesPembayaran($pembelian, $data['metode_pembayaran'], $data['id_akun_kas'] ?? null, $userId);

            // 6. Catat Riwayat
            RiwayatAktivitas::create([
                'id_pengguna' => $userId,
                'aktivitas' => "Mencatat pembelian Pullet ({$data['jenis_ayam']}, {$data['jumlah_awal']} ekor). Batch baru tercipta: {$kodeBatch}.",
            ]);

            return $pembelian;
        });
    }

    private function generateNoFakturBeli()
    {
        return \App\Helpers\CodeGenerator::generate('PB', 'pembelian', 'no_faktur_beli');
    }

    private function prosesPembayaran(Pembelian $pembelian, $metode, $idAkunKas, $userId)
    {
        if ($metode === 'LUNAS') {
            if (empty($idAkunKas)) {
                throw new \Exception("Rekening sumber dana wajib dipilih untuk pembayaran LUNAS.");
            }

            $akun = AkunKas::lockForUpdate()->findOrFail($idAkunKas);
            
            // Kurangi saldo
            if ($akun->saldo_sekarang < $pembelian->total_pembelian) {
                throw new \Exception("Saldo kas tidak mencukupi untuk pembayaran lunas.");
            }
            $akun->saldo_sekarang -= $pembelian->total_pembelian;
            $akun->save();

            // Entry Buku Kas
            $kodeJurnal = \App\Helpers\CodeGenerator::generate('BK', 'buku_kas', 'kode_jurnal', 4);

            BukuKas::create([
                'kode_jurnal' => $kodeJurnal,
                'id_akun' => $akun->id_akun,
                'id_pengguna' => $userId,
                'tanggal_transaksi' => Carbon::now(),
                'jenis' => 'Keluar',
                'tipe_referensi' => 'pembelian',
                'id_referensi' => $pembelian->id_pembelian,
                'nominal' => $pembelian->total_pembelian,
                'keterangan' => "Pembelian {$pembelian->kategori_pembelian} ({$pembelian->no_faktur_beli})",
            ]);

        } elseif ($metode === 'TEMPO') {
            Hutang::create([
                'id_pembelian' => $pembelian->id_pembelian,
                'jumlah_hutang' => $pembelian->total_pembelian,
                'sisa_hutang' => $pembelian->total_pembelian,
                'status_hutang' => 'Belum Lunas',
            ]);
        } else {
            throw new \Exception("Metode pembayaran tidak valid.");
        }
    }
}
