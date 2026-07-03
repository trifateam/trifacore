<?php

use App\Models\Batch;
use App\Models\Kandang;
use App\Models\Pengguna;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->admin = Pengguna::create([
        'nama_lengkap' => 'Admin',
        'username' => 'admin',
        'password' => bcrypt('password'),
        'role' => 'Admin',
    ]);

    $this->kandang = Kandang::create([
        'nama_kandang' => 'Kandang Alpha',
        'tahun_masuk' => 2023,
        'status' => 'Tidak Aktif',
        'id_pengguna' => $this->admin->id_pengguna
    ]);

    $this->batch = Batch::create([
        'kode_batch' => 'BCH-001',
        'nama_batch' => '',
        'populasi_awal' => 1000,
        'populasi_saat_ini' => 1000,
        'tgl_masuk' => now()->toDateString(),
        'status_batch' => 'Pending'
    ]);
});

test('admin bisa memproses form assign kandang untuk batch', function () {
    $response = $this->actingAs($this->admin)->post("/batch/assign/{$this->batch->id_batch}", [
        'id_kandang' => $this->kandang->id_kandang,
        'tgl_afkir' => Carbon::now()->addWeeks(90)->toDateString()
    ]);

    $response->assertSessionHasNoErrors();
    $response->assertRedirect('/batch');

    $this->assertDatabaseHas('batch', [
        'id_batch' => $this->batch->id_batch,
        'id_kandang' => $this->kandang->id_kandang,
        'status_batch' => 'Aktif',
        'nama_batch' => 'BCH-001 / Kandang Alpha'
    ]);


});
