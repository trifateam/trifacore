<?php

use App\Models\KategoriBiaya;
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

test('admin can view kategori biaya list', function () {
    KategoriBiaya::create([
        'nama_kategori' => 'Listrik',
        'keterangan' => 'Biaya Listrik Bulanan',
    ]);

    $response = $this->actingAs($this->admin)->get('/master-data/kategori-biaya');

    $response->assertStatus(200);
    $response->assertSee('Listrik');
});

test('admin can create new kategori biaya', function () {
    $response = $this->actingAs($this->admin)->post('/master-data/kategori-biaya', [
        'nama_kategori' => 'Air',
        'keterangan' => 'Biaya Air Bulanan',
    ]);

    $response->assertRedirect('/master-data/kategori-biaya');
    $response->assertSessionHas('success');

    $this->assertDatabaseHas('kategori_biaya', [
        'nama_kategori' => 'Air',
        'keterangan' => 'Biaya Air Bulanan',
    ]);
});

test('admin can update existing kategori biaya', function () {
    $kategori = KategoriBiaya::create([
        'nama_kategori' => 'Internet',
        'keterangan' => 'Biaya Wifi',
    ]);

    $response = $this->actingAs($this->admin)->put("/master-data/kategori-biaya/{$kategori->id_kategori_biaya}", [
        'nama_kategori' => 'Internet Fiber',
        'keterangan' => 'Biaya Wifi Cepat',
    ]);

    $response->assertRedirect('/master-data/kategori-biaya');
    $response->assertSessionHas('success');

    $this->assertDatabaseHas('kategori_biaya', [
        'id_kategori_biaya' => $kategori->id_kategori_biaya,
        'nama_kategori' => 'Internet Fiber',
        'keterangan' => 'Biaya Wifi Cepat',
    ]);
});

test('admin can delete kategori biaya if not used in operasional', function () {
    $kategori = KategoriBiaya::create([
        'nama_kategori' => 'Kategori Hapus',
        'keterangan' => 'Akan Dihapus',
    ]);

    $response = $this->actingAs($this->admin)->delete("/master-data/kategori-biaya/{$kategori->id_kategori_biaya}");

    $response->assertRedirect('/master-data/kategori-biaya');
    $response->assertSessionHas('success');

    $this->assertDatabaseMissing('kategori_biaya', [
        'id_kategori_biaya' => $kategori->id_kategori_biaya,
    ]);
});

test('kategori biaya validation requires necessary fields', function () {
    $response = $this->actingAs($this->admin)->post('/master-data/kategori-biaya', []);

    $response->assertSessionHasErrors([
        'nama_kategori',
    ]);
});
