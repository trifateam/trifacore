<?php

use App\Models\Barang;
use App\Models\Batch;
use App\Models\Kandang;
use App\Models\KonsumsiPakan;
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
        'kode_batch' => 'B-'.rand(100, 999),
        'nama_batch' => 'Batch 01',
        'id_kandang' => $this->kandang->id_kandang,
        'populasi_awal' => 1000,
        'populasi_saat_ini' => 1000,
        'tgl_masuk' => Carbon::today()->toDateString(),
        'umur_awal_minggu' => 1,
        'status_batch' => 'Aktif',
    ]);

    $this->pakan = Barang::create([
        'id_pengguna' => $this->pegawai->id_pengguna,
        'nama_barang' => 'Pakan Starter',
        'kategori_barang' => 'Pakan',
        'satuan' => 'kg',
        'stok_barang' => 100,
        'dapat_dijual' => 0,
        'dapat_dibeli' => 1,
    ]);
});

test('pegawai can view konsumsi pakan index', function () {
    $response = $this->actingAs($this->pegawai)->get('/pencatatan/konsumsi-pakan');

    $response->assertStatus(200);
    $response->assertSee('Kandang A');
    $response->assertSee('Batch 01');
});

test('pegawai can store konsumsi pakan and reduce stok', function () {
    $response = $this->actingAs($this->pegawai)->post("/pencatatan/konsumsi-pakan/{$this->batch->id_batch}", [
        'id_barang' => $this->pakan->id_barang,
        'jumlah_pakan_kg' => 20,
        'waktu_pemberian' => '07:00',
    ]);

    $response->assertRedirect('/pencatatan/konsumsi-pakan');
    $response->assertSessionHas('success');

    $this->assertDatabaseHas('konsumsi_pakan', [
        'id_batch' => $this->batch->id_batch,
        'id_barang' => $this->pakan->id_barang,
        'jumlah_pakan_kg' => 20,
    ]);

    $this->assertDatabaseHas('barang', [
        'id_barang' => $this->pakan->id_barang,
        'stok_barang' => 80, // 100 - 20
    ]);
});

test('pegawai cannot store konsumsi pakan if stok insufficient', function () {
    $response = $this->actingAs($this->pegawai)->post("/pencatatan/konsumsi-pakan/{$this->batch->id_batch}", [
        'id_barang' => $this->pakan->id_barang,
        'jumlah_pakan_kg' => 150, // More than 100
        'waktu_pemberian' => '07:00',
    ]);

    $response->assertSessionHas('error');

    $this->assertDatabaseMissing('konsumsi_pakan', [
        'id_batch' => $this->batch->id_batch,
    ]);

    $this->assertDatabaseHas('barang', [
        'id_barang' => $this->pakan->id_barang,
        'stok_barang' => 100, // Stok unchanged
    ]);
});

test('pegawai cannot store more than 2 sessions per day for the same batch', function () {
    // Sesi 1
    KonsumsiPakan::create([
        'kode_pakan' => 'KP-1',
        'id_batch' => $this->batch->id_batch,
        'id_barang' => $this->pakan->id_barang,
        'id_pengguna' => $this->pegawai->id_pengguna,
        'tanggal_konsumsi' => Carbon::today()->toDateString(),
        'jumlah_pakan_kg' => 10,
    ]);

    // Sesi 2
    KonsumsiPakan::create([
        'kode_pakan' => 'KP-2',
        'id_batch' => $this->batch->id_batch,
        'id_barang' => $this->pakan->id_barang,
        'id_pengguna' => $this->pegawai->id_pengguna,
        'tanggal_konsumsi' => Carbon::today()->toDateString(),
        'jumlah_pakan_kg' => 10,
    ]);

    // Attempt Sesi 3
    $response = $this->actingAs($this->pegawai)->post("/pencatatan/konsumsi-pakan/{$this->batch->id_batch}", [
        'id_barang' => $this->pakan->id_barang,
        'jumlah_pakan_kg' => 10,
        'waktu_pemberian' => '17:00',
    ]);

    $response->assertRedirect('/pencatatan/konsumsi-pakan');
    $response->assertSessionHas('error');

    $this->assertEquals(2, KonsumsiPakan::count());
});
