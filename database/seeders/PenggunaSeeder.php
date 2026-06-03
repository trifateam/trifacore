<?php

namespace Database\Seeders;

use App\Models\Pengguna;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class PenggunaSeeder extends Seeder
{
    /**
     * Seed data default pengguna.
     */
    public function run(): void
    {
        Pengguna::create([
            'nama_lengkap' => 'Administrator',
            'username'     => 'admin',
            'password'     => Hash::make('password'),
            'role'         => 'Admin',
        ]);
    }
}
