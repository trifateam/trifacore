<?php

use App\Models\Pengguna;
use App\Models\Supplier;
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

test('admin can view supplier list', function () {
    Supplier::create([
        'id_pengguna' => $this->admin->id_pengguna,
        'nama_supplier' => 'PT Pakan Subur',
        'alamat_supplier' => 'Jl. Pakan No 1',
        'kontak_supplier' => '08123456789',
        'email' => 'contact@pakansubur.com',
        'nama_pic' => 'Budi',
    ]);

    $response = $this->actingAs($this->admin)->get('/master-data/supplier');

    $response->assertStatus(200);
    $response->assertSee('PT Pakan Subur');
});

test('admin can create new supplier', function () {
    $response = $this->actingAs($this->admin)->post('/master-data/supplier', [
        'nama_supplier' => 'PT Vitamin Sehat',
        'alamat_supplier' => 'Jl. Vitamin No 2',
        'kontak_supplier' => '08987654321',
        'email' => 'sales@vitaminsehat.com',
        'nama_pic' => 'Andi',
    ]);

    $response->assertRedirect('/master-data/supplier');
    $response->assertSessionHas('success');

    $this->assertDatabaseHas('supplier', [
        'nama_supplier' => 'PT Vitamin Sehat',
        'alamat_supplier' => 'Jl. Vitamin No 2',
        'kontak_supplier' => '08987654321',
        'email' => 'sales@vitaminsehat.com',
    ]);
});

test('admin can update existing supplier', function () {
    $supplier = Supplier::create([
        'id_pengguna' => $this->admin->id_pengguna,
        'nama_supplier' => 'Supplier Lama',
        'alamat_supplier' => 'Jl. Lama',
        'kontak_supplier' => '0811111111',
    ]);

    $response = $this->actingAs($this->admin)->put("/master-data/supplier/{$supplier->id_supplier}", [
        'nama_supplier' => 'Supplier Baru',
        'alamat_supplier' => 'Jl. Baru',
        'kontak_supplier' => '0822222222',
    ]);

    $response->assertRedirect('/master-data/supplier');
    $response->assertSessionHas('success');

    $this->assertDatabaseHas('supplier', [
        'id_supplier' => $supplier->id_supplier,
        'nama_supplier' => 'Supplier Baru',
        'kontak_supplier' => '0822222222',
    ]);
});

test('admin can delete supplier if no transactions exist', function () {
    $supplier = Supplier::create([
        'id_pengguna' => $this->admin->id_pengguna,
        'nama_supplier' => 'To Be Deleted Supplier',
        'alamat_supplier' => 'Unknown',
        'kontak_supplier' => '0000000',
    ]);

    $response = $this->actingAs($this->admin)->delete("/master-data/supplier/{$supplier->id_supplier}");

    $response->assertRedirect('/master-data/supplier');
    $response->assertSessionHas('success');

    $this->assertDatabaseMissing('supplier', [
        'id_supplier' => $supplier->id_supplier,
    ]);
});

test('supplier validation requires necessary fields', function () {
    $response = $this->actingAs($this->admin)->post('/master-data/supplier', []);

    $response->assertSessionHasErrors([
        'nama_supplier',
        'alamat_supplier',
        'kontak_supplier',
    ]);
});
