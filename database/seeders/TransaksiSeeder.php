<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Supplier;
use App\Models\Pelanggan;
use App\Models\Barang;
use App\Models\AkunKas;
use App\Models\Pengguna;
use App\Services\TransaksiPembelianService;
use App\Services\TransaksiPenjualanService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class TransaksiSeeder extends Seeder
{
    public function run(): void
    {
        $supplier = Supplier::first();
        $pelanggan = Pelanggan::first();
        $akunKas = AkunKas::where('kategori_akun', 'Bank')->first();
        $admin = Pengguna::where('role', 'Admin')->first();

        // Login as admin for services
        auth()->login($admin);

        $pembelianService = new TransaksiPembelianService();
        $penjualanService = new TransaksiPenjualanService();

        $pakanStarter = Barang::where('nama_barang', 'Pakan Starter (Awal)')->first();
        $pakanLayer = Barang::where('nama_barang', 'Pakan Layer (Petelur)')->first();
        $vitamin = Barang::where('nama_barang', 'Vitamin Ayam Petelur')->first();
        
        $telurRB = Barang::where('nama_barang', 'Telur RB')->first();
        $telurMB = Barang::where('nama_barang', 'Telur MB')->first();
        $telurMK = Barang::where('nama_barang', 'Telur MK')->first();

        // Simulate 3 Purchases (Day -25, Day -15, Day -5)
        $purchaseDates = [25, 15, 5];
        foreach ($purchaseDates as $daysAgo) {
            $date = Carbon::now()->subDays($daysAgo);
            Carbon::setTestNow($date);

            $dataBeli = [
                'id_supplier' => $supplier->id_supplier,
                'metode_pembayaran' => 'LUNAS',
                'id_akun_kas' => $akunKas->id_akun,
                'total_pembelian' => 0, // dihitung ulang
                'catatan' => 'Pembelian rutin ' . $date->format('d M'),
            ];

            $qtyStarter = 2000;
            $qtyLayer = 3000;
            
            $subStarter = $qtyStarter * $pakanStarter->harga;
            $subLayer = $qtyLayer * $pakanLayer->harga;
            $dataBeli['total_pembelian'] = $subStarter + $subLayer;

            $detailsBeli = [
                ['id_barang' => $pakanStarter->id_barang, 'kuantitas' => $qtyStarter, 'harga_satuan' => $pakanStarter->harga, 'sub_total' => $subStarter],
                ['id_barang' => $pakanLayer->id_barang, 'kuantitas' => $qtyLayer, 'harga_satuan' => $pakanLayer->harga, 'sub_total' => $subLayer],
            ];

            try {
                $pembelianService->prosesBeliMaterial($dataBeli, $detailsBeli);
            } catch (\Exception $e) {
                Log::error("Seeder Pembelian Error: " . $e->getMessage());
            }
        }

        // Simulate 5 Sales (Day -20, Day -16, Day -10, Day -6, Day -2)
        $salesDates = [20, 16, 10, 6, 2];
        foreach ($salesDates as $daysAgo) {
            $date = Carbon::now()->subDays($daysAgo);
            Carbon::setTestNow($date);

            $dataJual = [
                'id_pelanggan' => $pelanggan->id_pelanggan,
                'metode_pembayaran' => ($daysAgo % 2 == 0) ? 'LUNAS' : 'PIUTANG',
                'id_akun_kas' => $akunKas->id_akun,
                'total_harga' => 0,
                'kategori_penjualan' => 'telur',
                'catatan' => 'Penjualan grosir ' . $date->format('d M'),
            ];

            // Jual dalam porsi besar (misal 5000 RB, 1000 MB, 500 MK)
            $qtyRB = rand(4000, 6000);
            $qtyMB = rand(800, 1500);
            $qtyMK = rand(200, 500);

            $subRB = $qtyRB * $telurRB->harga;
            $subMB = $qtyMB * $telurMB->harga;
            $subMK = $qtyMK * $telurMK->harga;
            
            $dataJual['total_harga'] = $subRB + $subMB + $subMK;

            $detailsJual = [
                ['id_barang' => $telurRB->id_barang, 'kuantitas' => $qtyRB, 'harga_satuan' => $telurRB->harga, 'sub_total' => $subRB],
                ['id_barang' => $telurMB->id_barang, 'kuantitas' => $qtyMB, 'harga_satuan' => $telurMB->harga, 'sub_total' => $subMB],
                ['id_barang' => $telurMK->id_barang, 'kuantitas' => $qtyMK, 'harga_satuan' => $telurMK->harga, 'sub_total' => $subMK],
            ];

            try {
                $penjualanService->prosesTransaksi($dataJual, $detailsJual);
            } catch (\Exception $e) {
                Log::error("Seeder Penjualan Error: " . $e->getMessage());
            }
        }

        // Reset time back to normal
        Carbon::setTestNow();
        auth()->logout();
    }
}
