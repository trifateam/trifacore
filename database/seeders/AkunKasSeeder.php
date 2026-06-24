<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AkunKas;

class AkunKasSeeder extends Seeder
{
    public function run(): void
    {
        AkunKas::query()->delete();
        $data = [
            ['nama_akun' => 'Kas Tunai', 'kategori_akun' => 'Tunai', 'no_rekening' => null, 'nama_pemilik' => null, 'saldo' => 10000000,],
            ['nama_akun' => 'Bank BCA', 'kategori_akun' => 'Bank', 'no_rekening' => '0123456789', 'nama_pemilik' => 'CV Berkah Unggas', 'saldo' => 100000000,],
            ['nama_akun' => 'Bank Mandiri', 'kategori_akun' => 'Bank', 'no_rekening' => '137000123456', 'nama_pemilik' => 'CV Berkah Unggas', 'saldo' => 50000000,],
        ];

        foreach ($data as $item) {
            AkunKas::create($item);
        }
    }
}
