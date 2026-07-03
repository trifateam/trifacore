<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use App\Http\Requests\KandangRequest;
use App\Models\Batch;
use App\Models\Kandang;
use App\Services\AuditService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KandangController extends Controller
{
    /**
     * Display a listing of the kandang.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        $kandangs = Kandang::query()->withTrashed()
            ->when($search, function ($query, $search) {
                $query->where('nama_kandang', 'like', "%{$search}%");
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10)
            ->appends($request->only('search'));

        return view('master-data.kandang.index', compact('kandangs', 'search'));
    }

    /**
     * Show the form for creating a new kandang.
     */
    public function create()
    {
        return view('master-data.kandang.create');
    }

    /**
     * Store a newly created kandang in storage.
     */
    public function store(KandangRequest $request)
    {
        Kandang::create([
            'id_pengguna' => Auth::id(),
            'nama_kandang' => $request->nama_kandang,
            'populasi_saat_ini' => 0,
            'tahun_masuk' => $request->tahun_masuk,
        ]);

        AuditService::log('Menambah kandang baru: '.$request->nama_kandang);

        return redirect()->route('master-data.kandang.index')
            ->with('success', 'Data kandang berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified kandang.
     */
    public function edit($id)
    {
        $kandang = Kandang::withTrashed()->findOrFail($id);

        return view('master-data.kandang.edit', compact('kandang'));
    }

    /**
     * Update the specified kandang in storage.
     */
    public function update(KandangRequest $request, $id)
    {
        $kandang = Kandang::withTrashed()->findOrFail($id);

        $kandang->update([
            'nama_kandang' => $request->nama_kandang,
            'tahun_masuk' => $request->tahun_masuk,
        ]);

        if ($request->status === 'non-aktif' && ! $kandang->trashed()) {
            $kandang->delete();
        } elseif ($request->status === 'aktif' && $kandang->trashed()) {
            $kandang->restore();
        }

        AuditService::log('Mengedit kandang: '.$request->nama_kandang);

        return redirect()->route('master-data.kandang.index')
            ->with('success', 'Data kandang berhasil diperbarui.');
    }

    /**
     * Remove the specified kandang from storage.
     */
    public function destroy($id)
    {
        $kandang = Kandang::withTrashed()->findOrFail($id);

        // Cek apakah kandang masih punya batch aktif
        $activeBatches = Batch::where('id_kandang', $kandang->id_kandang)
            ->where('status_batch', 'Aktif')
            ->count();

        if ($activeBatches > 0) {
            return redirect()->route('master-data.kandang.index')
                ->with('error', "Kandang \"{$kandang->nama_kandang}\" tidak bisa dihapus karena masih memiliki {$activeBatches} batch aktif.");
        }

        // Soft delete
        $kandangName = $kandang->nama_kandang;
        $kandang->delete();

        AuditService::log('Menghapus kandang: '.$kandangName);

        return redirect()->route('master-data.kandang.index')
            ->with('success', 'Data kandang berhasil dihapus.');
    }
}
