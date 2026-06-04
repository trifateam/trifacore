<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SuhuKandang extends Model
{
    protected $table = 'suhu_kandang';
    protected $primaryKey = 'id_suhu';
    protected $guarded = [];

    protected $casts = [
        'tanggal_waktu' => 'datetime',
        'suhu' => 'decimal:2',
        'suhu_min' => 'decimal:2',
        'suhu_max' => 'decimal:2',
        'kelembaban' => 'decimal:2',
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
