<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Batch;
use App\Models\ProduksiTelur;
use App\Services\StokBarangService;
use App\Helpers\CodeGenerator;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class ProduksiTelurSeeder extends Seeder
{
    public function run(): void
    {
        ProduksiTelur::query()->delete();
        $batches = Batch::with('kandang')->where('status_batch', 'Aktif')->get();
        $stokService = new StokBarangService();
        $admin = \App\Models\Pengguna::where('role', 'Admin')->first();
        $id_pengguna = $admin ? $admin->id_pengguna : 1;

        foreach ($batches as $batch) {
            $startDate = Carbon::now()->subDays(30);
            $endDate = Carbon::now();
            $populasi = $batch->jumlah_sisa; // Asumsi konstan untuk seeder sederhana, aslinya deplesi jalan harian

            for ($date = $startDate; $date->lte($endDate); $date->addDay()) {
                // HDP antara 75% - 85%
                $hdp = rand(750, 850) / 1000;
                $totalTelur = round($populasi * $hdp);

                $rb = round($totalTelur * 0.75); // 75% RB
                $mb = round($totalTelur * 0.15); // 15% MB
                $mk = round($totalTelur * 0.08); // 8% MK
                $pecah = $totalTelur - $rb - $mb - $mk; // 2% Pecah

                $totalBerat = ($rb * 0.06) + ($mb * 0.055) + ($mk * 0.05) + ($pecah * 0.05); // asumsi per butir

                $kode = CodeGenerator::generate('PT', 'produksi_telur', 'kode_produksi');

                ProduksiTelur::create([
                    'kode_produksi' => $kode,
                    'id_batch' => $batch->id_batch,
                    'id_pengguna' => $id_pengguna,
                    'tanggal_produksi' => $date->toDateString(),
                    'jml_telur_rb' => $rb,
                    'jml_telur_mb' => $mb,
                    'jml_telur_mk' => $mk,
                    'jml_telur_pecah' => $pecah,
                    'total_berat_kg' => round($totalBerat, 2),
                    'created_at' => $date,
                    'updated_at' => $date,
                ]);

                // Tambah stok
                try {
                    $stokService->tambahStokTelur('Telur RB', $rb);
                    $stokService->tambahStokTelur('Telur MB', $mb);
                    $stokService->tambahStokTelur('Telur MK', $mk);
                    $stokService->tambahStokTelur('Telur Pecah', $pecah);
                } catch (\Exception $e) {
                    Log::error($e->getMessage());
                }
            }
        }
    }
}
