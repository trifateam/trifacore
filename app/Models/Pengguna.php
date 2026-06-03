<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Pengguna extends Authenticatable
{
    use Notifiable;

    /**
     * Nama tabel yang digunakan model ini.
     *
     * @var string
     */
    protected $table = 'pengguna';

    /**
     * Primary key tabel.
     *
     * @var string
     */
    protected $primaryKey = 'id_pengguna';

    /**
     * Kolom yang boleh diisi secara mass-assignment.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nama_lengkap',
        'username',
        'password',
        'role',
    ];

    /**
     * Kolom yang disembunyikan dari serialisasi (JSON/array).
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
    ];

    /**
     * Attribute casting.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    public function pelanggan()
    {
        return $this->hasMany(Pelanggan::class, 'id_pengguna', 'id_pengguna');
    }

    public function supplier()
    {
        return $this->hasMany(Supplier::class, 'id_pengguna', 'id_pengguna');
    }

    public function barang()
    {
        return $this->hasMany(Barang::class, 'id_pengguna', 'id_pengguna');
    }

    public function kandang()
    {
        return $this->hasMany(Kandang::class, 'id_pengguna', 'id_pengguna');
    }
}
