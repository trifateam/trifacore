<?php

use App\Models\Barang;
use App\Models\Kandang;
use App\Models\Pengguna;
use App\Models\ProduksiPupukKandang;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->pegawai = Pengguna::create([
        'nama_lengkap' => 'Pegawai Test',
        'username' => 'pegawai_test',
        'password' => bcrypt('password123'),
        'role' => 'Pegawai Kandang',
    ]);

    $this->kandang = Kandang::create([
        'id_pengguna' => $this->pegawai->id_pengguna, 
        'nama_kandang' => 'Kandang Pupuk',
        'kapasitas' => 1000,
        'status_kandang' => 'Aktif',
    ]);

    $this->pupuk = Barang::create([
        'id_pengguna' => $this->pegawai->id_pengguna,
        'nama_barang' => 'Pupuk Organik',
        'kategori_barang' => 'Pupuk',
        'satuan' => 'kg',
        'stok_barang' => 100,
        'dapat_dijual' => 1,
        'dapat_dibeli' => 0,
    ]);
});

test('pegawai can view produksi pupuk index', function () {
    $response = $this->actingAs($this->pegawai)->get('/pencatatan/pupuk');

    $response->assertStatus(200);
    $response->assertSee('Kandang Pupuk');
});

test('pegawai can store produksi pupuk and increase stok', function () {
    $response = $this->actingAs($this->pegawai)->post("/pencatatan/pupuk/{$this->kandang->id_kandang}", [
        'tanggal_kumpul' => Carbon::today()->toDateString(),
        'jumlah_karung' => 10,
        'total_berat_kg' => 500,
    ]);

    $response->assertRedirect('/pencatatan/pupuk');
    $response->assertSessionHas('success');

    $this->assertDatabaseHas('produksi_pupuk_kandang', [
        'id_kandang' => $this->kandang->id_kandang,
        'jumlah_karung' => 10,
        'total_berat_kg' => 500,
    ]);

    $this->assertDatabaseHas('barang', [
        'id_barang' => $this->pupuk->id_barang,
        'stok_barang' => 600, // 100 + 500
    ]);
});

test('pegawai cannot store produksi pupuk if both inputs are zero', function () {
    $response = $this->actingAs($this->pegawai)->post("/pencatatan/pupuk/{$this->kandang->id_kandang}", [
        'tanggal_kumpul' => Carbon::today()->toDateString(),
        'jumlah_karung' => 0,
        'total_berat_kg' => 0,
    ]);

    $response->assertSessionHas('error');

    $this->assertDatabaseMissing('produksi_pupuk_kandang', [
        'id_kandang' => $this->kandang->id_kandang,
    ]);

    $this->assertDatabaseHas('barang', [
        'id_barang' => $this->pupuk->id_barang,
        'stok_barang' => 100, // Stok unchanged
    ]);
});
