<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BukuKas extends Model
{
    use HasFactory;

    protected $table = 'buku_kas';
    protected $primaryKey = 'id_buku_kas';
    protected $guarded = [];

    public function akunKas()
    {
        return $this->belongsTo(AkunKas::class, 'id_akun', 'id_akun');
    }

    public function pengguna()
    {
        return $this->belongsTo(Pengguna::class, 'id_pengguna', 'id_pengguna');
    }
}
