<?php

use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('halaman utama (landing page) bisa diakses', function () {
    $response = $this->get('/');

    $response->assertStatus(200);
});

test('pengunjung bisa mengirimkan form testimoni', function () {
    $response = $this->post(route('testimoni.store'), [
        'nama' => 'John Doe',
        'role' => 'Peternak Ayam',
        'teks' => 'Aplikasi yang sangat membantu operasional!',
    ]);

    $response->assertRedirect('/#testimoni');
    $response->assertSessionHas('success', 'Terima kasih! Ulasan Anda telah dikirim.');

    $this->assertDatabaseHas('testimonis', [
        'nama' => 'John Doe',
        'role' => 'Peternak Ayam',
        'teks' => 'Aplikasi yang sangat membantu operasional!',
        'rating' => 5,
        'is_tampil' => 1,
    ]);
});

test('form testimoni memvalidasi input wajib', function () {
    $response = $this->post(route('testimoni.store'), [
        'nama' => '',
        'teks' => '',
    ]);

    $response->assertSessionHasErrors(['nama', 'teks']);
});
