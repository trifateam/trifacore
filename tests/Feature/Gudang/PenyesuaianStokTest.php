<?php

use App\Models\Barang;
use App\Models\Pengguna;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->admin = Pengguna::create([
        'nama_lengkap' => 'Admin',
        'username' => 'admin',
        'password' => bcrypt('password'),
        'role' => 'Admin',
    ]);

    $this->barang = Barang::create([
        'nama_barang' => 'Pakan Starter',
        'kategori_barang' => 'Pakan',
        'satuan' => 'Kg',
        'stok_barang' => 100,
        'dapat_dibeli' => 1,
        'dapat_dijual' => 0,
        'id_pengguna' => $this->admin->id_pengguna,
    ]);
});

test('admin bisa menyesuaikan stok fisik (stock opname) gudang', function () {
    $response = $this->actingAs($this->admin)->post("/gudang/adjust/{$this->barang->id_barang}", [
        'stok_fisik' => 90,
        'alasan' => 'Ada 10 kg karung rusak',
    ]);

    $response->assertSessionHasNoErrors();
    $response->assertRedirect('/gudang/stok-konsumsi');

    $this->assertDatabaseHas('barang', [
        'id_barang' => $this->barang->id_barang,
        'stok_barang' => 90,
    ]);

    $this->assertDatabaseHas('log_penyesuaian_stok', [
        'id_barang' => $this->barang->id_barang,
        'stok_lama' => 100,
        'stok_baru' => 90,
        'alasan' => 'Ada 10 kg karung rusak',
    ]);
});
