<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Pengguna;
use Illuminate\Support\Facades\Hash;

class PenggunaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Bersihkan data pengguna sebelumnya jika ada, agar bersih (opsional)
        Pengguna::query()->delete();

        $users = [
            [
                'username' => 'admin',
                'password' => Hash::make('password123'),
                'nama_lengkap' => 'Administrator System',
                'role' => 'Admin',

            ],
            [
                'username' => 'owner',
                'password' => Hash::make('password123'),
                'nama_lengkap' => 'Bapak Budi (Owner)',
                'role' => 'Owner',

            ],
            [
                'username' => 'sales',
                'password' => Hash::make('password123'),
                'nama_lengkap' => 'Agus Sales',
                'role' => 'Sales',

            ],
            [
                'username' => 'kandang_a',
                'password' => Hash::make('password123'),
                'nama_lengkap' => 'Joko Kandang',
                'role' => 'Pegawai Kandang',

            ],
            [
                'username' => 'gudang1',
                'password' => Hash::make('password123'),
                'nama_lengkap' => 'Bambang Gudang',
                'role' => 'Pegawai Gudang',

            ],
        ];

        foreach ($users as $user) {
            Pengguna::create($user);
        }
    }
}
