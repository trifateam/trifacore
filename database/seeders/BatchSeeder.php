<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BatchSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $kandang1 = \App\Models\Kandang::where('nama_kandang', 'Kandang A')->first();
        $kandang2 = \App\Models\Kandang::where('nama_kandang', 'Kandang B')->first();
        $supplier = \App\Models\Supplier::first();

        \App\Models\Batch::create([
            'kode_batch' => 'BTC-20230101-01',
            'id_kandang' => $kandang1 ? $kandang1->id_kandang : null,
            'nama_batch' => 'Batch Pullet 2023 A',
            'jenis_ayam' => 'Lohmann Brown',
            'tgl_masuk' => '2023-01-01',
            'umur_awal_minggu' => 16,
            'populasi_awal' => 5000,
            'status_batch' => 'Aktif',
            'id_supplier' => $supplier ? $supplier->id_supplier : null,
            'harga_per_ekor' => 65000,
            'jumlah_sisa' => 0,
        ]);

        \App\Models\Batch::create([
            'kode_batch' => 'BTC-20240101-02',
            'id_kandang' => $kandang2 ? $kandang2->id_kandang : null,
            'nama_batch' => 'Batch Pullet 2024 B',
            'jenis_ayam' => 'Lohmann Brown',
            'tgl_masuk' => '2024-01-01',
            'umur_awal_minggu' => 16,
            'populasi_awal' => 5000,
            'status_batch' => 'Aktif',
            'id_supplier' => $supplier ? $supplier->id_supplier : null,
            'harga_per_ekor' => 67000,
            'jumlah_sisa' => 0,
        ]);

        \App\Models\Batch::create([
            'kode_batch' => 'BTC-20260701-03',
            'id_kandang' => null,
            'nama_batch' => 'Batch Pullet 2026 Baru',
            'jenis_ayam' => 'Hisex Brown',
            'tgl_masuk' => '2026-07-01',
            'umur_awal_minggu' => 15,
            'populasi_awal' => 4500,
            'status_batch' => 'Pending',
            'id_supplier' => $supplier ? $supplier->id_supplier : null,
            'harga_per_ekor' => 70000,
            'jumlah_sisa' => 4500,
        ]);
    }
}
