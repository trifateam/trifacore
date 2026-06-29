<?php

namespace Database\Seeders;

use App\Helpers\CodeGenerator;
use App\Models\Batch;
use App\Models\Kandang;
use App\Models\Pengguna;
use App\Models\Supplier;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class BatchSeeder extends Seeder
{
    public function run(): void
    {
        Batch::query()->delete();
        $admin = Pengguna::where('role', 'Admin')->first();
        $kandangs = Kandang::all();
        $supplier = Supplier::first();

        if ($kandangs->count() >= 3) {
            $data = [
                [
                    'id_kandang' => $kandangs[0]->id_kandang,
                    'nama_batch' => 'Batch '.Carbon::now()->format('Y/m').' - K1',
                    'jenis_ayam' => 'Lohmann Brown',
                    'tgl_masuk' => Carbon::now()->subDays(60)->toDateString(),
                    'umur_awal_minggu' => 18,
                    'populasi_awal' => 2900,
                    'jumlah_sisa' => 2900,
                    'status_batch' => 'Aktif',
                    'id_supplier' => $supplier->id_supplier ?? null,
                    'harga_per_ekor' => 75000,
                ],
                [
                    'id_kandang' => $kandangs[1]->id_kandang,
                    'nama_batch' => 'Batch '.Carbon::now()->format('Y/m').' - K2',
                    'jenis_ayam' => 'Lohmann Brown',
                    'tgl_masuk' => Carbon::now()->subDays(30)->toDateString(),
                    'umur_awal_minggu' => 16,
                    'populasi_awal' => 3000,
                    'jumlah_sisa' => 3000,
                    'status_batch' => 'Aktif',
                    'id_supplier' => $supplier->id_supplier ?? null,
                    'harga_per_ekor' => 70000,
                ],
                [
                    'id_kandang' => $kandangs[2]->id_kandang,
                    'nama_batch' => 'Batch '.Carbon::now()->format('Y/m').' - K3',
                    'jenis_ayam' => 'ISA Brown',
                    'tgl_masuk' => Carbon::now()->addDays(5)->toDateString(),
                    'umur_awal_minggu' => 14,
                    'populasi_awal' => 2500,
                    'jumlah_sisa' => 2500,
                    'status_batch' => 'Pending',
                    'id_supplier' => $supplier->id_supplier ?? null,
                    'harga_per_ekor' => 65000,
                ],
            ];

            foreach ($data as $item) {
                $item['kode_batch'] = CodeGenerator::generate('BTC', 'batch', 'kode_batch');
                $batch = Batch::create($item);

                if ($batch->status_batch === 'Aktif') {
                    $kandang = Kandang::find($batch->id_kandang);
                    if ($kandang) {
                        $kandang->populasi_saat_ini += $batch->jumlah_sisa;
                        $kandang->save();
                    }
                }
            }
        }
    }
}
