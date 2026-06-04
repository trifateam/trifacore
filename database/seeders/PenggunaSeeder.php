<?php

namespace Database\Seeders;

use App\Models\Pengguna;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class PenggunaSeeder extends Seeder
{
    /**
     * Seed data default pengguna untuk setiap role.
     */
    public function run(): void
    {
        $users = [
            [
                'nama_lengkap' => 'Administrator',
                'username'     => 'admin',
                'password'     => Hash::make('password'),
                'role'         => 'Admin',
            ],
            [
                'nama_lengkap' => 'Pemilik Peternakan',
                'username'     => 'owner',
                'password'     => Hash::make('password'),
                'role'         => 'Owner',
            ],
            [
                'nama_lengkap' => 'Pegawai Kandang 1',
                'username'     => 'pegawai1',
                'password'     => Hash::make('password'),
                'role'         => 'Pegawai Kandang',
            ],
            [
                'nama_lengkap' => 'Sales 1',
                'username'     => 'sales1',
                'password'     => Hash::make('password'),
                'role'         => 'Sales',
            ],
            [
                'nama_lengkap' => 'Pegawai Gudang 1',
                'username'     => 'gudang1',
                'password'     => Hash::make('password'),
                'role'         => 'Pegawai Gudang',
            ],
        ];

        foreach ($users as $user) {
            Pengguna::updateOrCreate(
                ['username' => $user['username']],
                $user
            );
        }
    }
}
