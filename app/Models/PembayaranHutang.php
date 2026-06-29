<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PembayaranHutang extends Model
{
    use HasFactory;

    protected $table = 'pembayaran_hutang';

    protected $primaryKey = 'id_pembayaran_hutang';

    protected $guarded = [];

    public function hutang()
    {
        return $this->belongsTo(Hutang::class, 'id_hutang', 'id_hutang');
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
