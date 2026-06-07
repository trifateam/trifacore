<?php

namespace App\Http\Controllers;

use App\Models\RiwayatAktivitas;
use Illuminate\Http\Request;

class RiwayatAktivitasController extends Controller
{
    /**
     * Tampilkan halaman riwayat aktivitas (audit trail).
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $tanggal_mulai = $request->input('tanggal_mulai');
        $tanggal_selesai = $request->input('tanggal_selesai');

        $riwayats = RiwayatAktivitas::with('pengguna')
            ->when($search, function ($query, $search) {
                $query->whereHas('pengguna', function($q) use ($search) {
                    $q->where('nama_lengkap', 'like', "%{$search}%")
                      ->orWhere('username', 'like', "%{$search}%");
                })->orWhere('aktivitas', 'like', "%{$search}%");
            })
            ->when($tanggal_mulai, function ($query, $tanggal_mulai) {
                $query->whereDate('created_at', '>=', $tanggal_mulai);
            })
            ->when($tanggal_selesai, function ($query, $tanggal_selesai) {
                $query->whereDate('created_at', '<=', $tanggal_selesai);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(20)
            ->appends($request->only(['search', 'tanggal_mulai', 'tanggal_selesai']));

        return view('riwayat-aktivitas.index', compact('riwayats', 'search', 'tanggal_mulai', 'tanggal_selesai'));
    }
}
