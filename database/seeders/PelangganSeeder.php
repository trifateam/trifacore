<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Pelanggan;
use App\Models\Pengguna;

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

            ],
            [
                'id_pengguna' => $admin->id_pengguna ?? 1,
                'nama_lengkap' => 'Toko Sembako Maju',
                'kategori' => 'Retail',
                'kontak' => '082233445566',
                'alamat' => 'Jl. Kebon Jeruk No 12',

            ],
            [
                'id_pengguna' => $admin->id_pengguna ?? 1,
                'nama_lengkap' => 'Supermarket Harmoni',
                'kategori' => 'Retail',
                'kontak' => '083344556677',
                'alamat' => 'Mall Harmoni Indah',

            ],
            [
                'id_pengguna' => $admin->id_pengguna ?? 1,
                'nama_lengkap' => 'Ibu Fatimah (Catering)',
                'kategori' => 'Personal',
                'kontak' => '084455667788',
                'alamat' => 'Perumahan Asri Blok C',

            ],
            [
                'id_pengguna' => $admin->id_pengguna ?? 1,
                'nama_lengkap' => 'Petani Sayur Mang Udin',
                'kategori' => 'Personal', // Biasanya beli pupuk
                'kontak' => '085566778899',
                'alamat' => 'Desa Tani Makmur',

            ],
        ];

        foreach ($data as $item) {
            Pelanggan::create($item);
        }
    }
}
