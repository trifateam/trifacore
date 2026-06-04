<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Piutang extends Model
{
    use HasFactory;

    protected $table = 'piutang';
    protected $primaryKey = 'id_piutang';

    protected $fillable = [
        'id_penjualan',
        'jumlah_piutang',
        'sisa_piutang',
        'status_piutang',
        'tanggal_jatuh_tempo',
        'tanggal_pelunasan',
    ];

    protected $casts = [
        'jumlah_piutang' => 'decimal:2',
        'sisa_piutang' => 'decimal:2',
        'tanggal_jatuh_tempo' => 'date',
        'tanggal_pelunasan' => 'datetime',
    ];

    public function penjualan()
    {
        return $this->belongsTo(Penjualan::class, 'id_penjualan', 'id_penjualan');
    }

    public function pembayaranPiutang()
    {
        return $this->hasMany(PembayaranPiutang::class, 'id_piutang', 'id_piutang');
    }
}
