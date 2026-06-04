<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProduksiPupukKandang extends Model
{
    protected $table = 'produksi_pupuk_kandang';
    protected $primaryKey = 'id_pupuk';
    protected $guarded = [];

    protected $casts = [
        'tanggal_kumpul' => 'date',
        'total_berat_kg' => 'decimal:2',
        'tanggal_catat' => 'datetime',
    ];

    public function kandang()
    {
        return $this->belongsTo(Kandang::class, 'id_kandang', 'id_kandang');
    }

    public function pengguna()
    {
        return $this->belongsTo(Pengguna::class, 'id_pengguna', 'id_pengguna');
    }
}
