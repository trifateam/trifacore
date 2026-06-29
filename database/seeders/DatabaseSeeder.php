<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Nonaktifkan foreign key checks untuk menghindari error saat reset
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        $this->call([
            PenggunaSeeder::class,
            SettingsSeeder::class,
            KategoriBiayaSeeder::class,
            AkunKasSeeder::class,
            SupplierSeeder::class,
            PelangganSeeder::class,
            BarangSeeder::class,
            KandangSeeder::class,
            BatchSeeder::class,

            // Eksekusi Logika Simulasi:
            ProduksiTelurSeeder::class,
            KonsumsiPakanSeeder::class,
            DeplesiSeeder::class,
            SuhuKandangSeeder::class,
            TransaksiSeeder::class, // Menangani pembelian pakan untuk stok historis + penjualan telur historis
            OperasionalSeeder::class,
        ]);

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
