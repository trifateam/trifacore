<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use App\Http\Requests\BarangRequest;
use App\Models\Barang;
use App\Services\AuditService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BarangController extends Controller
{
    /**
     * Display a listing of the barang.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $kategori = $request->input('kategori');

        $barangs = Barang::query()
            ->when($search, function ($query, $search) {
                $query->where('nama_barang', 'like', "%{$search}%");
            })
            ->when($kategori, function ($query, $kategori) {
                $query->where('kategori_barang', $kategori);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10)
            ->appends($request->only(['search', 'kategori']));

        $kategoriList = ['Telur', 'Pakan', 'Vitamin', 'Pupuk', 'Obat', 'Lainnya'];

        return view('master-data.barang.index', compact('barangs', 'search', 'kategori', 'kategoriList'));
    }

    /**
     * Show the form for creating a new barang.
     */
    public function create()
    {
        $kategoriList = ['Telur', 'Pakan', 'Vitamin', 'Pupuk', 'Obat', 'Lainnya'];

        return view('master-data.barang.create', compact('kategoriList'));
    }

    /**
     * Store a newly created barang in storage.
     */
    public function store(BarangRequest $request)
    {
        Barang::create([
            'id_pengguna' => Auth::id(),
            'nama_barang' => $request->nama_barang,
            'kategori_barang' => $request->kategori_barang,
            'sku' => $request->sku,
            'satuan' => $request->satuan,
            'stok_barang' => $request->stok_barang ?? 0,
            'stok_minimum' => $request->stok_minimum ?? 0,
            'harga' => $request->harga ?? 0,
            'dapat_dijual' => $request->dapat_dijual,
            'dapat_dibeli' => $request->dapat_dibeli,
        ]);

        AuditService::log('Menambah barang baru: '.$request->nama_barang);

        return redirect()->route('master-data.barang.index')
            ->with('success', 'Data barang berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified barang.
     */
    public function edit($id)
    {
        $barang = Barang::findOrFail($id);
        $kategoriList = ['Telur', 'Pakan', 'Vitamin', 'Pupuk', 'Obat', 'Lainnya'];

        return view('master-data.barang.edit', compact('barang', 'kategoriList'));
    }

    /**
     * Update the specified barang in storage.
     * Stok tidak bisa diubah via edit — hanya via transaksi/opname.
     */
    public function update(BarangRequest $request, $id)
    {
        $barang = Barang::findOrFail($id);

        $barang->update([
            'nama_barang' => $request->nama_barang,
            'kategori_barang' => $request->kategori_barang,
            'sku' => $request->sku,
            'satuan' => $request->satuan,
            'stok_minimum' => $request->stok_minimum ?? 0,
            'harga' => $request->harga ?? 0,
            'dapat_dijual' => $request->dapat_dijual,
            'dapat_dibeli' => $request->dapat_dibeli,
        ]);

        AuditService::log('Mengedit barang: '.$request->nama_barang);

        return redirect()->route('master-data.barang.index')
            ->with('success', 'Data barang berhasil diperbarui.');
    }

    /**
     * Remove the specified barang from storage.
     */
    public function destroy($id)
    {
        $barang = Barang::findOrFail($id);

        // Cek apakah barang sudah dipakai di transaksi
        $usedInTransactions = DB::table('detail_penjualan')->where('id_barang', $barang->id_barang)->exists()
            || DB::table('detail_pembelian')->where('id_barang', $barang->id_barang)->exists()
            || DB::table('konsumsi_pakan')->where('id_barang', $barang->id_barang)->exists()
            || DB::table('konsumsi_vitamin')->where('id_barang', $barang->id_barang)->exists();

        if ($usedInTransactions) {
            return redirect()->route('master-data.barang.index')
                ->with('error', "Barang \"{$barang->nama_barang}\" tidak bisa dihapus karena sudah digunakan dalam transaksi.");
        }

        $barangName = $barang->nama_barang;
        $barang->delete();

        AuditService::log('Menghapus barang: '.$barangName);

        return redirect()->route('master-data.barang.index')
            ->with('success', 'Data barang berhasil dihapus.');
    }
}
