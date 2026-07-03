<?php

use App\Models\Pengguna;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('halaman login bisa dirender', function () {
    $response = $this->get('/login');

    $response->assertStatus(200);
});

test('pengguna bisa melakukan login menggunakan username', function () {
    $pengguna = Pengguna::create([
        'nama_lengkap' => 'Test User',
        'username' => 'testuser',
        'password' => bcrypt('password123'),
        'role' => 'Admin',
    ]);

    $response = $this->post('/login', [
        'username' => 'testuser',
        'password' => 'password123',
    ]);

    $this->assertAuthenticatedAs($pengguna);
    $response->assertRedirect('/dashboard');
});

test('pengguna tidak bisa login dengan password yang salah', function () {
    $pengguna = Pengguna::create([
        'nama_lengkap' => 'Test User',
        'username' => 'testuser2',
        'password' => bcrypt('password123'),
        'role' => 'Admin',
    ]);

    $response = $this->post('/login', [
        'username' => 'testuser2',
        'password' => 'wrongpassword',
    ]);

    $this->assertGuest();
    $response->assertSessionHasErrors([
        'username' => 'Username atau password salah.',
    ]);
});

test('pengguna wajib mengisi username dan password', function () {
    $response = $this->post('/login', [
        'username' => '',
        'password' => '',
    ]);

    $response->assertSessionHasErrors(['username', 'password']);
});

test('pengguna bisa melakukan logout', function () {
    $pengguna = Pengguna::create([
        'nama_lengkap' => 'Test User',
        'username' => 'testuser3',
        'password' => bcrypt('password123'),
        'role' => 'Admin',
    ]);

    $response = $this->actingAs($pengguna)->post('/logout');

    $this->assertGuest();
    $response->assertRedirect('/login');
});
