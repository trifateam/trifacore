<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Setting;

class SettingsSeeder extends Seeder
{
    public function run()
    {
        $settings = [
            ['key' => 'nama_peternakan', 'value' => 'TriFaCore Farm'],
            ['key' => 'alamat', 'value' => 'Jl. Contoh Peternakan No. 123, Desa Sukamaju, Kecamatan Peternakan'],
            ['key' => 'no_telp', 'value' => '081234567890'],
            ['key' => 'email', 'value' => 'info@trifacore.com'],
            ['key' => 'nama_pemilik', 'value' => 'Bpk. Ahmad Peternak'],
            ['key' => 'jabatan_pemilik', 'value' => 'Owner'],
            ['key' => 'visi_misi', 'value' => 'Menjadi peternakan ayam petelur terdepan dengan inovasi teknologi dan manajemen modern.'],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(['key' => $setting['key']], ['value' => $setting['value']]);
        }
    }
}
