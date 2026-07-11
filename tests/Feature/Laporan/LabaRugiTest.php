<?php

use App\Models\Pengguna;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->owner = Pengguna::create([
        'nama_lengkap' => 'Owner Test',
        'username' => 'owner_test',
        'password' => bcrypt('password123'),
        'role' => 'Owner',
    ]);
});

test('owner can view laba rugi report', function () {
    $response = $this->actingAs($this->owner)->get('/laporan/laba-rugi');
    
    $response->assertStatus(200);
});
