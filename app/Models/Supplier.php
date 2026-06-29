<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;

    protected $table = 'supplier';

    protected $primaryKey = 'id_supplier';

    protected $fillable = [
        'id_pengguna',
        'nama_supplier',
        'kontak_supplier',
        'alamat_supplier',
        'nama_pic',
        'email',
    ];

    public function pengguna()
    {
        return $this->belongsTo(Pengguna::class, 'id_pengguna', 'id_pengguna');
    }
}
