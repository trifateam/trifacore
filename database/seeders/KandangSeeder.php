<?php

namespace Database\Seeders;

use App\Models\Kandang;
use App\Models\Pengguna;
use Illuminate\Database\Seeder;

class KandangSeeder extends Seeder
{
    public function run(): void
    {
        Kandang::query()->delete();
        $admin = Pengguna::where('role', 'Admin')->first();

        $data = [
            [
                'id_pengguna' => $admin->id_pengguna ?? 1,
                'nama_kandang' => 'Kandang A (Utama)',
                'populasi_saat_ini' => 0, // Akan diisi Batch
                'tahun_masuk' => 2024,

            ],
            [
                'id_pengguna' => $admin->id_pengguna ?? 1,
                'nama_kandang' => 'Kandang B (Cadangan)',
                'populasi_saat_ini' => 0, // Akan diisi Batch
                'tahun_masuk' => 2025,

            ],
            [
                'id_pengguna' => $admin->id_pengguna ?? 1,
                'nama_kandang' => 'Kandang C (Karantina)',
                'populasi_saat_ini' => 0,
                'tahun_masuk' => 2025,

            ],
        ];

        foreach ($data as $item) {
            Kandang::create($item);
        }
    }
}
