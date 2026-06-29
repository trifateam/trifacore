<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Kandang extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'kandang';

    protected $primaryKey = 'id_kandang';

    protected $fillable = [
        'id_pengguna',
        'nama_kandang',
        'kapasitas_kandang',
        'populasi_saat_ini',
        'tahun_masuk',
    ];

    public function pengguna()
    {
        return $this->belongsTo(Pengguna::class, 'id_pengguna', 'id_pengguna');
    }

    public function batches()
    {
        return $this->hasMany(Batch::class, 'id_kandang', 'id_kandang');
    }

    public function suhuKandang()
    {
        return $this->hasMany(SuhuKandang::class, 'id_kandang', 'id_kandang');
    }

    public function produksiPupukKandang()
    {
        return $this->hasMany(ProduksiPupukKandang::class, 'id_kandang', 'id_kandang');
    }
}
