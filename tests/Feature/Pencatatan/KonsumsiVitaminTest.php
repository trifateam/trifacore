<?php

use App\Models\Barang;
use App\Models\Batch;
use App\Models\Kandang;
use App\Models\KonsumsiVitamin;
use App\Models\Pengguna;
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
        'nama_kandang' => 'Kandang A',
        'kapasitas' => 1000,
        'status_kandang' => 'Aktif',
    ]);

    $this->batch = Batch::create([
        'kode_batch' => 'B-' . rand(100, 999),
        'nama_batch' => 'Batch 01',
        'id_kandang' => $this->kandang->id_kandang,
        'populasi_awal' => 1000,
        'populasi_saat_ini' => 1000,
        'tgl_masuk' => Carbon::today()->toDateString(),
        'umur_awal_minggu' => 1,
        'status_batch' => 'Aktif',
    ]);

    $this->vitamin = Barang::create([
        'id_pengguna' => $this->pegawai->id_pengguna,
        'nama_barang' => 'Vitamin Sehat',
        'kategori_barang' => 'Vitamin',
        'satuan' => 'liter',
        'stok_barang' => 10,
        'dapat_dijual' => 0,
        'dapat_dibeli' => 1,
    ]);
});

test('pegawai can view konsumsi vitamin index', function () {
    $response = $this->actingAs($this->pegawai)->get('/pencatatan/konsumsi-vitamin');

    $response->assertStatus(200);
    $response->assertSee('Kandang A');
    $response->assertSee('Batch 01');
});

test('pegawai can store konsumsi vitamin and reduce stok', function () {
    $response = $this->actingAs($this->pegawai)->post("/pencatatan/konsumsi-vitamin/{$this->batch->id_batch}", [
        'id_barang' => $this->vitamin->id_barang,
        'dosis' => 0.5,
        'total_penggunaan' => 2,
        'metode_pemberian' => 'Air Minum',
        'waktu_pemberian' => '08:00',
    ]);

    $response->assertRedirect('/pencatatan/konsumsi-vitamin');
    $response->assertSessionHas('success');

    $this->assertDatabaseHas('konsumsi_vitamin', [
        'id_batch' => $this->batch->id_batch,
        'id_barang' => $this->vitamin->id_barang,
        'total_penggunaan' => 2,
        'metode_pemberian' => 'Air Minum',
    ]);

    $this->assertDatabaseHas('barang', [
        'id_barang' => $this->vitamin->id_barang,
        'stok_barang' => 8, // 10 - 2
    ]);
});

test('pegawai cannot store konsumsi vitamin if stok insufficient', function () {
    $response = $this->actingAs($this->pegawai)->post("/pencatatan/konsumsi-vitamin/{$this->batch->id_batch}", [
        'id_barang' => $this->vitamin->id_barang,
        'total_penggunaan' => 15, // More than 10
        'waktu_pemberian' => '08:00',
    ]);

    $response->assertSessionHas('error');

    $this->assertDatabaseMissing('konsumsi_vitamin', [
        'id_batch' => $this->batch->id_batch,
    ]);

    $this->assertDatabaseHas('barang', [
        'id_barang' => $this->vitamin->id_barang,
        'stok_barang' => 10, // Stok unchanged
    ]);
});
