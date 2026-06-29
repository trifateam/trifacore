<?php

namespace App\Services;

use App\Models\RiwayatAktivitas;
use Illuminate\Support\Facades\Auth;

class AuditService
{
    /**
     * Catat aktivitas pengguna ke tabel riwayat_aktivitas
     *
     * @param  string  $aktivitas  Deskripsi aktivitas
     * @param  int|null  $userId  ID pengguna (default: auth user)
     */
    public static function log($aktivitas, $userId = null)
    {
        RiwayatAktivitas::create([
            'id_pengguna' => $userId ?? Auth::id(),
            'aktivitas' => $aktivitas,
            // waktu_aktivitas sudah dihandle oleh timestamps `created_at` di migration sebelumnya,
            // atau kalau ada field custom, sesuaikan.
            // Di database migration, kolomnya adalah id_riwayat_aktivitas, id_pengguna, aktivitas, timestamps.
        ]);
    }
}
