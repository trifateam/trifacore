<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kandang;
use App\Models\SuhuKandang;
use Carbon\Carbon;

class SuhuKandangSeeder extends Seeder
{
    public function run(): void
    {
        SuhuKandang::query()->delete();
        $kandangs = Kandang::where('is_active', true)->get();
        $admin = \App\Models\Pengguna::where('role', 'Admin')->first();
        $id_pengguna = $admin ? $admin->id_pengguna : 1;

        foreach ($kandangs as $kandang) {
            $startDate = Carbon::now()->subDays(30);
            $endDate = Carbon::now();

            for ($date = $startDate; $date->lte($endDate); $date->addDay()) {
                $suhu = rand(270, 320) / 10;
                \App\Models\SuhuKandang::create([
                    'kode_suhu' => \App\Helpers\CodeGenerator::generate('SK', 'suhu_kandang', 'kode_suhu'),
                    'id_kandang' => $kandang->id_kandang,
                    'id_pengguna' => $id_pengguna,
                    'tanggal_waktu' => $date->copy()->setHour(rand(8, 15))->setMinute(rand(0, 59)),
                    'suhu' => $suhu,
                    'suhu_min' => $suhu - 2,
                    'suhu_max' => $suhu + 2,
                    'kelembaban' => rand(60, 80),
                    'created_at' => $date,
                    'updated_at' => $date,
                ]);
            }
        }
    }
}
