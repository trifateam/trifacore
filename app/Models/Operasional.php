<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Operasional extends Model
{
    use HasFactory;

    protected $table = 'operasional';
    protected $primaryKey = 'id_operasional';
    protected $guarded = [];

    public function pengguna()
    {
        return $this->belongsTo(Pengguna::class, 'id_pengguna', 'id_pengguna');
    }

    public function kategoriBiaya()
    {
        return $this->belongsTo(KategoriBiaya::class, 'id_kategori_biaya', 'id_kategori_biaya');
    }

    public function akunKas()
    {
        return $this->belongsTo(AkunKas::class, 'id_akun', 'id_akun');
    }
}
