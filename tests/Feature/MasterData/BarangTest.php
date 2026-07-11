<?php

use App\Models\Barang;
use App\Models\Pengguna;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->admin = Pengguna::create([
        'nama_lengkap' => 'Admin Test',
        'username' => 'admin_test',
        'password' => bcrypt('password123'),
        'role' => 'Admin',
    ]);
});

test('admin can view barang list', function () {
    Barang::create([
        'id_pengguna' => $this->admin->id_pengguna,
        'nama_barang' => 'Pakan Ayam Test',
        'kategori_barang' => 'Pakan',
        'satuan' => 'kg',
        'stok_barang' => 100,
        'stok_minimum' => 10,
        'harga' => 5000,
        'dapat_dijual' => 0,
        'dapat_dibeli' => 1,
    ]);

    $response = $this->actingAs($this->admin)->get('/master-data/barang');

    $response->assertStatus(200);
    $response->assertSee('Pakan Ayam Test');
});

test('admin can create new barang', function () {
    $response = $this->actingAs($this->admin)->post('/master-data/barang', [
        'nama_barang' => 'Vitamin Ayam Test',
        'kategori_barang' => 'Vitamin',
        'sku' => 'VIT-001',
        'satuan' => 'botol',
        'stok_barang' => 50,
        'stok_minimum' => 5,
        'harga' => 15000,
        'dapat_dijual' => 1,
        'dapat_dibeli' => 1,
    ]);

    $response->assertRedirect('/master-data/barang');
    $response->assertSessionHas('success');

    $this->assertDatabaseHas('barang', [
        'nama_barang' => 'Vitamin Ayam Test',
        'kategori_barang' => 'Vitamin',
        'sku' => 'VIT-001',
        'stok_barang' => 50,
    ]);
});

test('admin can update existing barang', function () {
    $barang = Barang::create([
        'id_pengguna' => $this->admin->id_pengguna,
        'nama_barang' => 'Pakan Lama',
        'kategori_barang' => 'Pakan',
        'satuan' => 'kg',
        'stok_barang' => 100,
        'stok_minimum' => 10,
        'harga' => 5000,
        'dapat_dijual' => 0,
        'dapat_dibeli' => 1,
    ]);

    $response = $this->actingAs($this->admin)->put("/master-data/barang/{$barang->id_barang}", [
        'nama_barang' => 'Pakan Baru',
        'kategori_barang' => 'Pakan',
        'sku' => 'PAK-001',
        'satuan' => 'kg',
        'stok_minimum' => 15,
        'harga' => 6000,
        'dapat_dijual' => 0,
        'dapat_dibeli' => 1,
    ]);

    $response->assertRedirect('/master-data/barang');
    $response->assertSessionHas('success');

    $this->assertDatabaseHas('barang', [
        'id_barang' => $barang->id_barang,
        'nama_barang' => 'Pakan Baru',
        'harga' => 6000,
    ]);
});

test('admin can delete barang if not used in transactions', function () {
    $barang = Barang::create([
        'id_pengguna' => $this->admin->id_pengguna,
        'nama_barang' => 'Barang Dihapus',
        'kategori_barang' => 'Obat',
        'satuan' => 'botol',
        'stok_barang' => 10,
        'stok_minimum' => 1,
        'harga' => 20000,
        'dapat_dijual' => 1,
        'dapat_dibeli' => 1,
    ]);

    $response = $this->actingAs($this->admin)->delete("/master-data/barang/{$barang->id_barang}");

    $response->assertRedirect('/master-data/barang');
    $response->assertSessionHas('success');

    $this->assertDatabaseMissing('barang', [
        'id_barang' => $barang->id_barang,
    ]);
});

test('barang validation requires necessary fields', function () {
    $response = $this->actingAs($this->admin)->post('/master-data/barang', []);

    $response->assertSessionHasErrors([
        'nama_barang',
        'kategori_barang',
        'satuan',
        'stok_barang',
        'stok_minimum',
        'harga',
        'dapat_dijual',
        'dapat_dibeli',
    ]);
});
