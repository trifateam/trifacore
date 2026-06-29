<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PembayaranPiutang extends Model
{
    use HasFactory;

    protected $table = 'pembayaran_piutang';

    protected $primaryKey = 'id_pembayaran_piutang';

    protected $guarded = [];

    public function piutang()
    {
        return $this->belongsTo(Piutang::class, 'id_piutang', 'id_piutang');
    }

    public function pengguna()
    {
        return $this->belongsTo(Pengguna::class, 'id_pengguna', 'id_pengguna');
    }

    public function akunKas()
    {
        return $this->belongsTo(AkunKas::class, 'id_akun', 'id_akun');
    }
}
