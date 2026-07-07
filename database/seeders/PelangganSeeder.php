<?php

namespace Database\Seeders;

use App\Models\Pelanggan;
use App\Models\Pengguna;
use Illuminate\Database\Seeder;

class PelangganSeeder extends Seeder
{
    public function run(): void
    {
        Pelanggan::query()->delete();
        $admin = Pengguna::where('role', 'Admin')->first();

        $data = [
            [
                'id_pengguna' => $admin->id_pengguna ?? 1,
                'nama_lengkap' => 'Agen Telur Berkah',
                'kategori' => 'Distributor',
                'kontak' => '081122334455',
                'alamat' => 'Pasar Induk Kramat Jati',
                'latitude' => '-0.947083', // Dummy coordinate Padang
                'longitude' => '100.417181',
            ],
            [
                'id_pengguna' => $admin->id_pengguna ?? 1,
                'nama_lengkap' => 'Toko Sembako Maju',
                'kategori' => 'Retail',
                'kontak' => '082233445566',
                'alamat' => 'Jl. Kebon Jeruk No 12',
                'latitude' => '-0.925642',
                'longitude' => '100.363989',
            ],
            [
                'id_pengguna' => $admin->id_pengguna ?? 1,
                'nama_lengkap' => 'Supermarket Harmoni',
                'kategori' => 'Retail',
                'kontak' => '083344556677',
                'alamat' => 'Mall Harmoni Indah',
                'latitude' => '-0.916723',
                'longitude' => '100.360142',
            ],
            [
                'id_pengguna' => $admin->id_pengguna ?? 1,
                'nama_lengkap' => 'Ibu Fatimah (Catering)',
                'kategori' => 'Personal',
                'kontak' => '084455667788',
                'alamat' => 'Perumahan Asri Blok C',
                'latitude' => '-0.932902',
                'longitude' => '100.370502',
            ],
            [
                'id_pengguna' => $admin->id_pengguna ?? 1,
                'nama_lengkap' => 'Petani Sayur Mang Udin',
                'kategori' => 'Personal', // Biasanya beli pupuk
                'kontak' => '085566778899',
                'alamat' => 'Desa Tani Makmur',
                'latitude' => '-0.950293',
                'longitude' => '100.420892',
            ],
        ];

        foreach ($data as $item) {
            Pelanggan::create($item);
        }
    }
}
