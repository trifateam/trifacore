<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AkunKas extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'akun_kas';
    protected $primaryKey = 'id_akun';
    
    protected $fillable = [
        'nama_akun',
        'kategori_akun',
        'no_rekening',
        'nama_pemilik',
        'saldo',
    ];
}
