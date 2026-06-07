<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use App\Http\Requests\PelangganRequest;
use App\Models\Pelanggan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PelangganController extends Controller
{
    /**
     * Display a listing of the pelanggan.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $kategori = $request->input('kategori');

        $pelanggans = Pelanggan::query()
            ->when($search, function ($query, $search) {
                $query->where('nama_lengkap', 'like', "%{$search}%");
            })
            ->when($kategori, function ($query, $kategori) {
                $query->where('kategori', $kategori);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10)
            ->appends($request->only(['search', 'kategori']));

        $kategoriList = ['Distributor', 'Retail', 'Personal'];

        return view('master-data.pelanggan.index', compact('pelanggans', 'search', 'kategori', 'kategoriList'));
    }

    /**
     * Store a newly created pelanggan in storage.
     */
    public function store(PelangganRequest $request)
    {
        Pelanggan::create([
            'id_pengguna' => Auth::id(),
            'nama_lengkap' => $request->nama_lengkap,
            'kategori' => $request->kategori,
            'kontak' => $request->kontak,
            'alamat' => $request->alamat,
            'is_active' => $request->is_active,
        ]);

        \App\Services\AuditService::log('Menambah pelanggan baru: ' . $request->nama_lengkap);

        return redirect()->route('master-data.pelanggan.index')
            ->with('success', 'Data pelanggan berhasil ditambahkan.');
    }

    /**
     * Update the specified pelanggan in storage.
     */
    public function update(PelangganRequest $request, $id)
    {
        $pelanggan = Pelanggan::findOrFail($id);

        $pelanggan->update([
            'nama_lengkap' => $request->nama_lengkap,
            'kategori' => $request->kategori,
            'kontak' => $request->kontak,
            'alamat' => $request->alamat,
            'is_active' => $request->is_active,
        ]);

        \App\Services\AuditService::log('Mengedit pelanggan: ' . $request->nama_lengkap);

        return redirect()->route('master-data.pelanggan.index')
            ->with('success', 'Data pelanggan berhasil diperbarui.');
    }

    /**
     * Remove the specified pelanggan from storage.
     */
    public function destroy($id)
    {
        $pelanggan = Pelanggan::findOrFail($id);

        // Cek apakah pelanggan masih punya transaksi penjualan
        $hasTransactions = DB::table('penjualan')
            ->where('id_pelanggan', $pelanggan->id_pelanggan)
            ->exists();

        if ($hasTransactions) {
            return redirect()->route('master-data.pelanggan.index')
                ->with('error', "Pelanggan \"{$pelanggan->nama_lengkap}\" tidak bisa dihapus karena masih memiliki transaksi penjualan. Sebaiknya non-aktifkan saja.");
        }

        $pelangganName = $pelanggan->nama_lengkap;
        $pelanggan->delete();

        \App\Services\AuditService::log('Menghapus pelanggan: ' . $pelangganName);

        return redirect()->route('master-data.pelanggan.index')
            ->with('success', 'Data pelanggan berhasil dihapus.');
    }
}
