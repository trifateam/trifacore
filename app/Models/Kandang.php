<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kandang extends Model
{
    use HasFactory;

    protected $table = 'kandang';
    protected $primaryKey = 'id_kandang';
    
    protected $fillable = [
        'id_pengguna',
        'nama_kandang',
        'kapasitas_kandang',
        'populasi_saat_ini',
        'tahun_masuk',
        'is_active',
    ];

    public function pengguna()
    {
        return $this->belongsTo(Pengguna::class, 'id_pengguna', 'id_pengguna');
    }
}
