<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\KategoriBiaya;

class KategoriBiayaSeeder extends Seeder
{
    public function run(): void
    {
        KategoriBiaya::query()->delete();
        $data = [
            ['nama_kategori' => 'Gaji Pegawai', 'keterangan' => 'Biaya gaji bulanan pegawai dan bonus'],
            ['nama_kategori' => 'Listrik & Air', 'keterangan' => 'Tagihan listrik kandang dan air'],
            ['nama_kategori' => 'Kesehatan Ternak', 'keterangan' => 'Pembelian obat, disinfektan, vaksin'],
            ['nama_kategori' => 'Transportasi & BBM', 'keterangan' => 'Biaya operasional kendaraan'],
            ['nama_kategori' => 'Maintenance Kandang', 'keterangan' => 'Perbaikan fasilitas dan alat'],
            ['nama_kategori' => 'ATK & Kantor', 'keterangan' => 'Kebutuhan alat tulis dan administrasi'],
            ['nama_kategori' => 'Lain-lain', 'keterangan' => 'Pengeluaran tak terduga'],
        ];

        foreach ($data as $item) {
            KategoriBiaya::create($item);
        }
    }
}
