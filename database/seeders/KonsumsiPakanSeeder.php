<?php

namespace Database\Seeders;

use App\Helpers\CodeGenerator;
use App\Models\Barang;
use App\Models\Batch;
use App\Models\KonsumsiPakan;
use App\Models\Pengguna;
use App\Services\StokBarangService;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;

class KonsumsiPakanSeeder extends Seeder
{
    public function run(): void
    {
        KonsumsiPakan::query()->delete();
        $batches = Batch::with('kandang')->where('status_batch', 'Aktif')->get();
        $stokService = new StokBarangService;
        $pakanStarter = Barang::where('nama_barang', 'Pakan Starter (Awal)')->first();
        $pakanLayer = Barang::where('nama_barang', 'Pakan Layer (Petelur)')->first();
        $admin = Pengguna::where('role', 'Admin')->first();
        $id_pengguna = $admin ? $admin->id_pengguna : 1;

        foreach ($batches as $batch) {
            $startDate = Carbon::now()->subDays(30);
            $endDate = Carbon::now();
            $populasi = $batch->populasi_saat_ini;

            for ($date = $startDate; $date->lte($endDate); $date->addDay()) {
                // Konsumsi rata-rata 110 gram per ekor per hari
                $konsumsiPerEkorGram = rand(108, 115);
                $totalKonsumsiKg = ($populasi * $konsumsiPerEkorGram) / 1000;

                $kode = CodeGenerator::generate('KP', 'konsumsi_pakan', 'kode_pakan');

                $pakanId = $pakanLayer->id_barang; // Default to layer
                if ($batch->umur_awal_minggu < 18) {
                    $pakanId = $pakanStarter->id_barang;
                }

                KonsumsiPakan::create([
                    'kode_pakan' => $kode,
                    'id_batch' => $batch->id_batch,
                    'id_pengguna' => $id_pengguna,
                    'id_barang' => $pakanId,
                    'tanggal_konsumsi' => $date->toDateString(),
                    'jumlah_pakan_kg' => round($totalKonsumsiKg, 2),
                    'waktu_pemberian' => '07:00:00',
                    'created_at' => $date,
                    'updated_at' => $date,
                ]);

                // Kurangi stok
                try {
                    $stokService->kurangiStokPakan($pakanId, round($totalKonsumsiKg, 2));
                } catch (\Exception $e) {
                    Log::error($e->getMessage());
                }
            }
        }
    }
}
