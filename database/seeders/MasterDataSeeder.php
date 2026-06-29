<?php

namespace Database\Seeders;

use App\Models\AkunKas;
use App\Models\Barang;
use App\Models\Kandang;
use App\Models\KategoriBiaya;
use App\Models\Pelanggan;
use App\Models\Pengguna;
use App\Models\Setting;
use App\Models\Supplier;
use Illuminate\Database\Seeder;

class MasterDataSeeder extends Seeder
{
    public function run(): void
    {
        $pengguna = Pengguna::first();
        $id_pengguna = $pengguna ? $pengguna->id_pengguna : 1;

        // Kategori Biaya
        $kategoriBiayas = [
            ['nama_kategori' => 'Gaji', 'keterangan' => 'Biaya gaji pegawai'],
            ['nama_kategori' => 'Listrik', 'keterangan' => 'Biaya listrik kandang dan kantor'],
            ['nama_kategori' => 'Kesehatan Ternak', 'keterangan' => 'Biaya obat dan vitamin'],
            ['nama_kategori' => 'Transportasi', 'keterangan' => 'Biaya bensin dan tol'],
            ['nama_kategori' => 'Maintenance', 'keterangan' => 'Perawatan kandang dan peralatan'],
        ];
        foreach ($kategoriBiayas as $kb) {
            KategoriBiaya::create($kb);
        }

        // Pelanggan
        $pelanggans = [
            ['id_pengguna' => $id_pengguna, 'nama_lengkap' => 'Toko Sembako Maju', 'kategori' => 'Distributor', 'kontak' => '081234567890', 'alamat' => 'Jl. Merdeka No. 1'],
            ['id_pengguna' => $id_pengguna, 'nama_lengkap' => 'Warung Pak Budi', 'kategori' => 'Retail', 'kontak' => '081298765432', 'alamat' => 'Jl. Sudirman No. 2'],
            ['id_pengguna' => $id_pengguna, 'nama_lengkap' => 'Ibu Siti', 'kategori' => 'Personal', 'kontak' => '081211112222', 'alamat' => 'Perum Indah Blok A/1'],
        ];
        foreach ($pelanggans as $p) {
            Pelanggan::create($p);
        }

        // Supplier
        $suppliers = [
            ['id_pengguna' => $id_pengguna, 'nama_supplier' => 'PT Pakan Unggas Nusantara', 'kontak_supplier' => '0211234567', 'alamat_supplier' => 'Kawasan Industri Cikarang', 'nama_pic' => 'Bapak Andi', 'email' => 'sales@pakanunggas.com'],
            ['id_pengguna' => $id_pengguna, 'nama_supplier' => 'CV Vitamin Sehat Ternak', 'kontak_supplier' => '0217654321', 'alamat_supplier' => 'Jl. Industri Raya', 'nama_pic' => 'Ibu Dina', 'email' => 'info@vitaminternak.com'],
        ];
        foreach ($suppliers as $s) {
            Supplier::create($s);
        }

        // Barang
        // Barang contoh: Telur RB, Telur MB, Telur MK, Telur Pecah, Pakan Utama, Vitamin A, Pupuk Kandang
        $barangs = [
            ['id_pengguna' => $id_pengguna, 'nama_barang' => 'Telur RB', 'kategori_barang' => 'Telur', 'satuan' => 'Kg', 'dapat_dijual' => true, 'dapat_dibeli' => false, 'harga' => 25000],
            ['id_pengguna' => $id_pengguna, 'nama_barang' => 'Telur MB', 'kategori_barang' => 'Telur', 'satuan' => 'Kg', 'dapat_dijual' => true, 'dapat_dibeli' => false, 'harga' => 24000],
            ['id_pengguna' => $id_pengguna, 'nama_barang' => 'Telur MK', 'kategori_barang' => 'Telur', 'satuan' => 'Kg', 'dapat_dijual' => true, 'dapat_dibeli' => false, 'harga' => 23000],
            ['id_pengguna' => $id_pengguna, 'nama_barang' => 'Telur Pecah', 'kategori_barang' => 'Telur', 'satuan' => 'Kg', 'dapat_dijual' => true, 'dapat_dibeli' => false, 'harga' => 15000],
            ['id_pengguna' => $id_pengguna, 'nama_barang' => 'Pakan Utama', 'kategori_barang' => 'Pakan', 'satuan' => 'Karung', 'dapat_dijual' => false, 'dapat_dibeli' => true, 'harga' => 350000],
            ['id_pengguna' => $id_pengguna, 'nama_barang' => 'Vitamin A', 'kategori_barang' => 'Vitamin', 'satuan' => 'Botol', 'dapat_dijual' => false, 'dapat_dibeli' => true, 'harga' => 50000],
            ['id_pengguna' => $id_pengguna, 'nama_barang' => 'Pupuk Kandang', 'kategori_barang' => 'Pupuk', 'satuan' => 'Karung', 'dapat_dijual' => true, 'dapat_dibeli' => false, 'harga' => 10000],
        ];
        foreach ($barangs as $b) {
            Barang::create($b);
        }

        // Kandang
        $kandangs = [
            ['id_pengguna' => $id_pengguna, 'nama_kandang' => 'Kandang A', 'kapasitas_kandang' => 5000, 'populasi_saat_ini' => 4800, 'tahun_masuk' => 2023],
            ['id_pengguna' => $id_pengguna, 'nama_kandang' => 'Kandang B', 'kapasitas_kandang' => 5000, 'populasi_saat_ini' => 5000, 'tahun_masuk' => 2024],
        ];
        foreach ($kandangs as $k) {
            Kandang::create($k);
        }

        // Akun Kas
        $akunKas = [
            ['nama_akun' => 'Kas Tunai', 'kategori_akun' => 'Tunai', 'saldo' => 5000000],
            ['nama_akun' => 'Bank BCA', 'kategori_akun' => 'Bank', 'no_rekening' => '1234567890', 'nama_pemilik' => 'Peternakan TriFa', 'saldo' => 25000000],
            ['nama_akun' => 'Bank Mandiri', 'kategori_akun' => 'Bank', 'no_rekening' => '0987654321', 'nama_pemilik' => 'Peternakan TriFa', 'saldo' => 15000000],
        ];
        foreach ($akunKas as $ak) {
            AkunKas::create($ak);
        }

        // Settings
        $settings = [
            ['key' => 'nama_peternakan', 'value' => 'TriFa Poultry Farm'],
            ['key' => 'alamat', 'value' => 'Jl. Peternakan Raya No. 1, Kab. Bogor'],
            ['key' => 'no_telp', 'value' => '08123456789'],
            ['key' => 'email', 'value' => 'info@trifafarm.com'],
            ['key' => 'nama_pemilik', 'value' => 'Bapak TriFa'],
        ];
        foreach ($settings as $set) {
            Setting::create($set);
        }
    }
}
