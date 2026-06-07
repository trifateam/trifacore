<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Barang;
use App\Models\Pengguna;

class BarangSeeder extends Seeder
{
    public function run(): void
    {
        Barang::query()->delete();
        $admin = Pengguna::where('role', 'Admin')->first();
        $id_pengguna = $admin->id_pengguna ?? 1;

        // Stok akan disimulasikan dari pembelian dan produksi, jadi awalnya biarkan 0 untuk telur
        // Tapi pakan harus ada stok awal sedikit jika tidak disimulasikan beli, namun kita akan beli di TransaksiSeeder.
        // Kita beri stok 0.
        
        $data = [
            // Telur (Produksi)
            ['id_pengguna' => $id_pengguna, 'nama_barang' => 'Telur RB', 'kategori_barang' => 'Telur', 'satuan' => 'butir', 'dapat_dijual' => true, 'dapat_dibeli' => false, 'harga' => 1800, 'stok_barang' => 0],
            ['id_pengguna' => $id_pengguna, 'nama_barang' => 'Telur MB', 'kategori_barang' => 'Telur', 'satuan' => 'butir', 'dapat_dijual' => true, 'dapat_dibeli' => false, 'harga' => 1500, 'stok_barang' => 0],
            ['id_pengguna' => $id_pengguna, 'nama_barang' => 'Telur MK', 'kategori_barang' => 'Telur', 'satuan' => 'butir', 'dapat_dijual' => true, 'dapat_dibeli' => false, 'harga' => 1200, 'stok_barang' => 0],
            ['id_pengguna' => $id_pengguna, 'nama_barang' => 'Telur Pecah', 'kategori_barang' => 'Telur', 'satuan' => 'butir', 'dapat_dijual' => true, 'dapat_dibeli' => false, 'harga' => 800, 'stok_barang' => 0],
            
            // Pakan (Pembelian) -> Set initial stock to 15000 so it can be consumed
            ['id_pengguna' => $id_pengguna, 'nama_barang' => 'Pakan Starter (Awal)', 'kategori_barang' => 'Pakan', 'satuan' => 'kg', 'dapat_dijual' => false, 'dapat_dibeli' => true, 'harga' => 8500, 'stok_barang' => 15000],
            ['id_pengguna' => $id_pengguna, 'nama_barang' => 'Pakan Layer (Petelur)', 'kategori_barang' => 'Pakan', 'satuan' => 'kg', 'dapat_dijual' => false, 'dapat_dibeli' => true, 'harga' => 8000, 'stok_barang' => 15000],
            
            // Vitamin
            ['id_pengguna' => $id_pengguna, 'nama_barang' => 'Vitamin Ayam Petelur', 'kategori_barang' => 'Vitamin', 'satuan' => 'botol', 'dapat_dijual' => false, 'dapat_dibeli' => true, 'harga' => 45000, 'stok_barang' => 100],
            ['id_pengguna' => $id_pengguna, 'nama_barang' => 'Vaksin ND', 'kategori_barang' => 'Obat', 'satuan' => 'botol', 'dapat_dijual' => false, 'dapat_dibeli' => true, 'harga' => 120000, 'stok_barang' => 50],
            
            // Pupuk
            ['id_pengguna' => $id_pengguna, 'nama_barang' => 'Pupuk Kandang (Kering)', 'kategori_barang' => 'Pupuk', 'satuan' => 'karung', 'dapat_dijual' => true, 'dapat_dibeli' => false, 'harga' => 10000, 'stok_barang' => 0],
        ];

        foreach ($data as $item) {
            Barang::create($item);
        }
    }
}
