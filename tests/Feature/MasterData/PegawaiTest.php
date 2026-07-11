<?php

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

test('admin can view pegawai list', function () {
    Pengguna::create([
        'nama_lengkap' => 'Pegawai Kandang 1',
        'username' => 'pegawai1',
        'password' => bcrypt('password'),
        'role' => 'Pegawai Kandang',
    ]);

    $response = $this->actingAs($this->admin)->get('/master-data/pegawai');

    $response->assertStatus(200);
    $response->assertSee('Pegawai Kandang 1');
});

test('admin can create new pegawai', function () {
    $response = $this->actingAs($this->admin)->post('/master-data/pegawai', [
        'nama_lengkap' => 'Admin Baru',
        'username' => 'adminbaru',
        'password' => 'password123',
        'password_confirmation' => 'password123',
        'role' => 'Admin',
    ]);

    $response->assertRedirect('/master-data/pegawai');
    $response->assertSessionHas('success');

    $this->assertDatabaseHas('pengguna', [
        'nama_lengkap' => 'Admin Baru',
        'username' => 'adminbaru',
        'role' => 'Admin',
    ]);
});

test('admin can update existing pegawai', function () {
    $pegawai = Pengguna::create([
        'nama_lengkap' => 'Sales Lama',
        'username' => 'sales_lama',
        'password' => bcrypt('password'),
        'role' => 'Sales',
    ]);

    $response = $this->actingAs($this->admin)->put("/master-data/pegawai/{$pegawai->id_pengguna}", [
        'nama_lengkap' => 'Sales Baru',
        'role' => 'Admin',
    ]);

    $response->assertRedirect('/master-data/pegawai');
    $response->assertSessionHas('success');

    $this->assertDatabaseHas('pengguna', [
        'id_pengguna' => $pegawai->id_pengguna,
        'nama_lengkap' => 'Sales Baru',
        'role' => 'Admin',
    ]);
});

test('admin cannot deactivate their own account', function () {
    $response = $this->actingAs($this->admin)->delete("/master-data/pegawai/{$this->admin->id_pengguna}");

    $response->assertRedirect('/master-data/pegawai');
    $response->assertSessionHas('error');

    $this->assertDatabaseHas('pengguna', [
        'id_pengguna' => $this->admin->id_pengguna,
        'deleted_at' => null,
    ]);
});

test('admin can toggle deactivate other pegawai', function () {
    $pegawai = Pengguna::create([
        'nama_lengkap' => 'Pegawai Hapus',
        'username' => 'pegawai_hapus',
        'password' => bcrypt('password'),
        'role' => 'Pegawai Gudang',
    ]);

    // Deactivate
    $response = $this->actingAs($this->admin)->delete("/master-data/pegawai/{$pegawai->id_pengguna}");

    $response->assertRedirect('/master-data/pegawai');
    $response->assertSessionHas('success');

    $this->assertSoftDeleted('pengguna', [
        'id_pengguna' => $pegawai->id_pengguna,
    ]);
});
