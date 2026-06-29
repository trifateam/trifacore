<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use App\Http\Requests\KategoriBiayaRequest;
use App\Models\KategoriBiaya;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KategoriBiayaController extends Controller
{
    /**
     * Display a listing of kategori biaya.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        $kategoris = KategoriBiaya::query()
            ->when($search, function ($query, $search) {
                $query->where('nama_kategori', 'like', "%{$search}%");
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10)
            ->appends($request->only(['search']));

        return view('master-data.kategori-biaya.index', compact('kategoris', 'search'));
    }

    /**
     * Store a newly created kategori biaya in storage.
     */
    public function store(KategoriBiayaRequest $request)
    {
        KategoriBiaya::create([
            'nama_kategori' => $request->nama_kategori,
            'keterangan' => $request->keterangan,
        ]);

        \App\Services\AuditService::log('Menambah kategori biaya baru: ' . $request->nama_kategori);

        return redirect()->route('master-data.kategori-biaya.index')
            ->with('success', 'Kategori biaya berhasil ditambahkan.');
    }

    /**
     * Update the specified kategori biaya in storage.
     */
    public function update(KategoriBiayaRequest $request, $id)
    {
        $kategori = KategoriBiaya::findOrFail($id);

        $kategori->update([
            'nama_kategori' => $request->nama_kategori,
            'keterangan' => $request->keterangan,
        ]);

        \App\Services\AuditService::log('Mengedit kategori biaya: ' . $request->nama_kategori);

        return redirect()->route('master-data.kategori-biaya.index')
            ->with('success', 'Kategori biaya berhasil diperbarui.');
    }

    /**
     * Remove the specified kategori biaya from storage.
     */
    public function destroy($id)
    {
        $kategori = KategoriBiaya::findOrFail($id);

        // Cek apakah kategori sudah dipakai di operasional
        $usedInOperasional = DB::table('operasional')
            ->where('id_kategori_biaya', $kategori->id_kategori_biaya)
            ->exists();

        if ($usedInOperasional) {
            return redirect()->route('master-data.kategori-biaya.index')
                ->with('error', "Kategori \"{$kategori->nama_kategori}\" tidak bisa dihapus karena sudah digunakan dalam biaya operasional.");
        }

        $kategoriName = $kategori->nama_kategori;
        $kategori->delete();

        \App\Services\AuditService::log('Menghapus kategori biaya: ' . $kategoriName);

        return redirect()->route('master-data.kategori-biaya.index')
            ->with('success', 'Kategori biaya berhasil dihapus.');
    }
}
