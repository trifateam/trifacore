<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hutang extends Model
{
    use HasFactory;

    protected $table = 'hutang';

    protected $primaryKey = 'id_hutang';

    protected $fillable = [
        'id_pembelian',
        'jumlah_hutang',
        'sisa_hutang',
        'status_hutang',
        'tanggal_jatuh_tempo',
        'tanggal_pelunasan',
    ];

    protected $casts = [
        'jumlah_hutang' => 'decimal:2',
        'sisa_hutang' => 'decimal:2',
        'tanggal_jatuh_tempo' => 'date',
        'tanggal_pelunasan' => 'datetime',
    ];

    public function pembelian()
    {
        return $this->belongsTo(Pembelian::class, 'id_pembelian', 'id_pembelian');
    }

    public function pembayaranHutang()
    {
        return $this->hasMany(PembayaranHutang::class, 'id_hutang', 'id_hutang');
    }
}
