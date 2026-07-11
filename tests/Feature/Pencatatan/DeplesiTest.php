<?php

use App\Models\Batch;
use App\Models\Kandang;
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

    $this->kandang = Kandang::create([
        'nama_kandang' => 'Kandang A',
        'tahun_masuk' => 2023,
        'id_pengguna' => $this->admin->id_pengguna,
    ]);
    $this->kandang->populasi_saat_ini = 1000;
    $this->kandang->save();

    $this->batch = Batch::create([
        'kode_batch' => 'BCH-01',
        'id_kandang' => $this->kandang->id_kandang,
        'kode_batch' => 'B-'.rand(100, 999),
        'nama_batch' => 'BCH-01/Kandang A',
        'populasi_awal' => 1000,
        'populasi_saat_ini' => 1000,
        'tgl_masuk' => now()->subDays(5)->toDateString(),
        'status_batch' => 'Aktif',
    ]);
});

test('pencatatan deplesi mengurangi populasi batch tetapi tidak menambah stok gudang', function () {
    $response = $this->actingAs($this->admin)->post("/pencatatan/deplesi/{$this->batch->id_batch}", [
        'jml_mati' => 5,
        'jml_cacat' => 2,
    ]);

    $response->assertSessionHasNoErrors();
    $response->assertRedirect('/pencatatan/deplesi');

    $this->assertDatabaseHas('deplesi', [
        'id_batch' => $this->batch->id_batch,
        'jml_mati' => 5,
        'jml_cacat' => 2,
    ]);

    $this->assertDatabaseHas('batch', [
        'id_batch' => $this->batch->id_batch,
        'populasi_saat_ini' => 993, // 1000 - 5 - 2
    ]);
});
