<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use App\Http\Requests\RekeningRequest;
use App\Models\AkunKas;
use Illuminate\Http\Request;

class RekeningController extends Controller
{
    /**
     * Display a listing of rekening kas/bank.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $kategori = $request->input('kategori');
        $status = $request->input('status');

        $rekenings = AkunKas::query()
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('nama_akun', 'like', "%{$search}%")
                      ->orWhere('no_rekening', 'like', "%{$search}%")
                      ->orWhere('nama_pemilik', 'like', "%{$search}%");
                });
            })
            ->when($kategori, function ($query, $kategori) {
                $query->where('kategori_akun', $kategori);
            })
            ->when($status !== null && $status !== '', function ($query) use ($status) {
                $query->where('is_active', $status);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10)
            ->appends($request->only(['search', 'kategori', 'status']));

        $kategoriList = ['Tunai', 'Bank', 'E-Wallet'];

        return view('master-data.rekening.index', compact('rekenings', 'search', 'kategori', 'status', 'kategoriList'));
    }

    /**
     * Store a newly created rekening in storage.
     */
    public function store(RekeningRequest $request)
    {
        AkunKas::create([
            'nama_akun' => $request->nama_akun,
            'kategori_akun' => $request->kategori_akun,
            'no_rekening' => $request->no_rekening,
            'nama_pemilik' => $request->nama_pemilik,
            'saldo' => $request->saldo ?? 0,
            'is_active' => $request->is_active,
        ]);

        return redirect()->route('master-data.rekening.index')
            ->with('success', 'Data rekening berhasil ditambahkan.');
    }

    /**
     * Update the specified rekening in storage.
     * Saldo TIDAK bisa diubah via edit — hanya via transaksi.
     */
    public function update(RekeningRequest $request, $id)
    {
        $rekening = AkunKas::findOrFail($id);

        $rekening->update([
            'nama_akun' => $request->nama_akun,
            'kategori_akun' => $request->kategori_akun,
            'no_rekening' => $request->no_rekening,
            'nama_pemilik' => $request->nama_pemilik,
            'is_active' => $request->is_active,
            // Saldo tidak diubah saat edit
        ]);

        return redirect()->route('master-data.rekening.index')
            ->with('success', 'Data rekening berhasil diperbarui.');
    }
}
