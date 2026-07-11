<?php

use App\Models\Batch;
use App\Models\Kandang;
use App\Models\Pengguna;
use App\Models\ProduksiTelur;
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
        'nama_kandang' => 'Kandang Riwayat',
        'kapasitas' => 1000,
        'status_kandang' => 'Aktif',
    ]);

    $this->batch = Batch::create([
        'kode_batch' => 'B-'.rand(100, 999),
        'nama_batch' => 'Batch Riwayat',
        'id_kandang' => $this->kandang->id_kandang,
        'populasi_awal' => 1000,
        'populasi_saat_ini' => 1000,
        'tgl_masuk' => Carbon::today()->toDateString(),
        'umur_awal_minggu' => 1,
        'status_batch' => 'Aktif',
    ]);
});

test('pegawai can view riwayat produksi telur', function () {
    ProduksiTelur::create([
        'kode_produksi' => 'PT-1',
        'id_batch' => $this->batch->id_batch,
        'id_pengguna' => $this->pegawai->id_pengguna,
        'tanggal_produksi' => Carbon::today()->toDateString(),
        'jml_telur_rb' => 100,
        'total_berat_kg' => 6.5,
    ]);

    $response = $this->actingAs($this->pegawai)->get('/pencatatan/riwayat/produksi-telur');

    $response->assertStatus(200);
    $response->assertSee('PT-1');
});

test('pegawai can view riwayat konsumsi pakan', function () {
    $response = $this->actingAs($this->pegawai)->get('/pencatatan/riwayat/konsumsi-pakan');
    $response->assertStatus(200);
});

test('pegawai can view riwayat konsumsi vitamin', function () {
    $response = $this->actingAs($this->pegawai)->get('/pencatatan/riwayat/konsumsi-vitamin');
    $response->assertStatus(200);
});

test('pegawai can view riwayat deplesi', function () {
    $response = $this->actingAs($this->pegawai)->get('/pencatatan/riwayat/deplesi');
    $response->assertStatus(200);
});

test('pegawai can view riwayat suhu kandang', function () {
    $response = $this->actingAs($this->pegawai)->get('/pencatatan/riwayat/suhu');
    $response->assertStatus(200);
});

test('pegawai can view riwayat produksi pupuk', function () {
    $response = $this->actingAs($this->pegawai)->get('/pencatatan/riwayat/pupuk');
    $response->assertStatus(200);
});
