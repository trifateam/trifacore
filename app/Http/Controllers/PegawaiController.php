<?php

namespace App\Http\Controllers;

use App\Services\PegawaiService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PegawaiController extends Controller
{
    public function __construct(
        private PegawaiService $service
    ) {}

    public function index(): View
    {
        return view('master.pegawai.index', [
            'pegawais' => $this->service->getAll(),
        ]);
    }

    public function create(): View
    {
        return view('master.pegawai.form');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nama'          => 'required|string|max:255',
            'jabatan'       => 'required|string|max:255',
            'no_hp'         => 'nullable|string|max:20',
            'alamat'        => 'nullable|string',
            'tanggal_masuk' => 'required|date',
            'status'        => 'required|in:aktif,nonaktif',
        ]);

        $this->service->create($validated);

        return redirect()->route('pegawai.index')
            ->with('success', 'Data pegawai berhasil ditambahkan.');
    }

    public function edit(int $id): View
    {
        return view('master.pegawai.form', [
            'pegawai' => $this->service->find($id),
        ]);
    }

    public function update(Request $request, int $id): RedirectResponse
    {
        $validated = $request->validate([
            'nama'          => 'required|string|max:255',
            'jabatan'       => 'required|string|max:255',
            'no_hp'         => 'nullable|string|max:20',
            'alamat'        => 'nullable|string',
            'tanggal_masuk' => 'required|date',
            'status'        => 'required|in:aktif,nonaktif',
        ]);

        $this->service->update($id, $validated);

        return redirect()->route('pegawai.index')
            ->with('success', 'Data pegawai berhasil diperbarui.');
    }

    public function destroy(int $id): RedirectResponse
    {
        $this->service->delete($id);

        return redirect()->route('pegawai.index')
            ->with('success', 'Data pegawai berhasil dihapus.');
    }
}
