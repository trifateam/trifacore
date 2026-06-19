<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use App\Http\Requests\PegawaiStoreRequest;
use App\Http\Requests\PegawaiUpdateRequest;
use App\Models\Pengguna;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class PegawaiController extends Controller
{
    /**
     * Display a listing of the pegawai.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        $pegawais = Pengguna::query()->withTrashed()
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('nama_lengkap', 'like', "%{$search}%")
                      ->orWhere('username', 'like', "%{$search}%");
                });
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10)
            ->appends($request->only('search'));

        return view('master-data.pegawai.index', compact('pegawais', 'search'));
    }

    /**
     * Store a newly created pegawai in storage.
     */
    public function store(PegawaiStoreRequest $request)
    {
        Pengguna::create([
            'nama_lengkap' => $request->nama_lengkap,
            'username' => $request->username,
            'password' => $request->password, // Auto-hashed by Pengguna model cast
            'role' => $request->role,
        ]);

        \App\Services\AuditService::log('Menambah pegawai baru: ' . $request->nama_lengkap);

        return redirect()->route('master-data.pegawai.index')
            ->with('success', 'Data pegawai berhasil ditambahkan.');
    }

    /**
     * Update the specified pegawai in storage.
     * Username bersifat immutable (tidak bisa diubah).
     */
    public function update(PegawaiUpdateRequest $request, $id)
    {
        $pegawai = Pengguna::findOrFail($id);

        $data = [
            'nama_lengkap' => $request->nama_lengkap,
            'role' => $request->role,
        ];

        // Password hanya diupdate jika diisi
        if ($request->filled('password')) {
            $data['password'] = $request->password; // Auto-hashed by Pengguna model cast
        }

        $pegawai->update($data);

        \App\Services\AuditService::log('Mengedit pegawai: ' . $request->nama_lengkap);

        return redirect()->route('master-data.pegawai.index')
            ->with('success', 'Data pegawai berhasil diperbarui.');
    }

    /**
     * Deactivate / Activate the specified pegawai.
     * Pegawai tidak bisa dihapus, hanya dinonaktifkan.
     * User tidak bisa menonaktifkan akun sendiri.
     */
    public function destroy($id)
    {
        $pegawai = Pengguna::findOrFail($id);

        // User tidak bisa menonaktifkan akun sendiri
        if ($pegawai->id_pengguna === Auth::id()) {
            return redirect()->route('master-data.pegawai.index')
                ->with('error', 'Anda tidak bisa menonaktifkan akun Anda sendiri.');
        }

        // Toggle status aktif
        if ($pegawai->trashed()) {
            $pegawai->restore();
            $status = 'diaktifkan';
        } else {
            $pegawai->delete();
            $status = 'dinonaktifkan';
        }

        \App\Services\AuditService::log('Mengubah status pegawai: ' . $pegawai->nama_lengkap . ' menjadi ' . $status);

        return redirect()->route('master-data.pegawai.index')
            ->with('success', "Pegawai \"{$pegawai->nama_lengkap}\" berhasil {$status}.");
    }
}
