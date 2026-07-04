<?php

namespace App\Services;

use App\Models\Batch;
use App\Models\Kandang;
use App\Models\ProduksiTelur;
use App\Models\KonsumsiPakan;
use App\Models\SuhuKandang;
use Carbon\Carbon;

class TaskNotificationService
{
    /**
     * Get uncompleted tasks for today for the given category.
     * Categories: 'telur', 'pakan', 'suhu'
     * Returns array of uncompleted entities (Kandang or Batch).
     */
    public static function getUncompletedTasks()
    {
        $today = Carbon::today();
        
        // Active Kandang (not deleted)
        $kandangs = Kandang::all();
        // Active Batches
        $batches = Batch::where('status_batch', 'Aktif')->get();

        $uncompletedTelur = [];
        $uncompletedPakan = [];
        $uncompletedSuhu = [];

        // Check Suhu Kandang (per Kandang)
        $suhuRecorded = SuhuKandang::whereDate('tanggal_waktu', $today)
            ->pluck('id_kandang')
            ->toArray();
            
        foreach ($kandangs as $kandang) {
            if (!in_array($kandang->id_kandang, $suhuRecorded)) {
                $uncompletedSuhu[] = $kandang;
            }
        }

        // Check Telur and Pakan (per Batch)
        $telurRecorded = ProduksiTelur::whereDate('tanggal_produksi', $today)
            ->pluck('id_batch')
            ->toArray();
            
        $pakanRecorded = KonsumsiPakan::whereDate('tanggal_konsumsi', $today)
            ->pluck('id_batch')
            ->toArray();

        foreach ($batches as $batch) {
            if (!in_array($batch->id_batch, $telurRecorded)) {
                $uncompletedTelur[] = $batch;
            }
            if (!in_array($batch->id_batch, $pakanRecorded)) {
                $uncompletedPakan[] = $batch;
            }
        }

        return [
            'telur' => collect($uncompletedTelur),
            'pakan' => collect($uncompletedPakan),
            'suhu' => collect($uncompletedSuhu),
            'has_any_task' => count($uncompletedTelur) > 0 || count($uncompletedPakan) > 0 || count($uncompletedSuhu) > 0,
        ];
    }
}
