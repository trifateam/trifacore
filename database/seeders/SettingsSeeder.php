<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    public function run(): void
    {
        Setting::query()->delete();
        $settings = [
            ['key' => 'nama_peternakan', 'value' => 'CV Berkah Unggas Mandiri'],
            ['key' => 'alamat', 'value' => 'Jl. Peternakan Raya No. 1, Kab. Bogor, Jawa Barat'],
            ['key' => 'no_telp', 'value' => '081234567890'],
            ['key' => 'email', 'value' => 'info@berkahunggas.com'],
            ['key' => 'nama_pemilik', 'value' => 'Bapak Budi Hartanto'],
            ['key' => 'jabatan_pemilik', 'value' => 'Direktur Utama'],
            ['key' => 'visi_misi', 'value' => 'Menjadi penyedia produk perunggasan terbaik dan terpercaya di Indonesia dengan mengutamakan kualitas, kesehatan ternak, dan keberlanjutan.'],
        ];

        foreach ($settings as $set) {
            Setting::create($set);
        }
    }
}
