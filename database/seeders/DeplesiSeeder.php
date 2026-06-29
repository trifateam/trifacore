<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Batch;
use App\Models\Deplesi;
use App\Models\Kandang;
use App\Helpers\CodeGenerator;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class DeplesiSeeder extends Seeder
{
    public function run(): void
    {
        Deplesi::query()->delete();
        $batches = Batch::where('status_batch', 'Aktif')->get();
        $admin = \App\Models\Pengguna::where('role', 'Admin')->first();
        $id_pengguna = $admin ? $admin->id_pengguna : 1;

        foreach ($batches as $batch) {
            $startDate = Carbon::now()->subDays(30);
            $endDate = Carbon::now();

            for ($date = $startDate; $date->lte($endDate); $date->addDay()) {
                // Mortalitas 0-3 ekor per hari
                $mati = rand(0, 2);
                $afkir = rand(0, 1);
                
                if ($mati == 0 && $afkir == 0 && rand(1, 10) > 4) {
                    // Beberapa hari tidak ada deplesi
                    continue; 
                }

                $total = $mati + $afkir;
                $kode = CodeGenerator::generate('DP', 'deplesi', 'kode_deplesi');

                Deplesi::create([
                    'kode_deplesi' => $kode,
                    'id_batch' => $batch->id_batch,
                    'id_pengguna' => $id_pengguna,
                    'tanggal_deplesi' => $date->toDateString(),
                    'jml_mati' => $mati,
                    'jml_afkir' => $afkir,
                    'created_at' => $date,
                    'updated_at' => $date,
                ]);

                // Kurangi populasi
                $batch->jumlah_sisa -= $total;
                $batch->save();

                $kandang = Kandang::find($batch->id_kandang);
                if ($kandang) {
                    $kandang->populasi_saat_ini -= $total;
                    $kandang->save();
                }
            }
        }
    }
}
