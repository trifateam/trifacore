<?php

namespace App\Http\Controllers\Pengaturan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\SettingService;
use Illuminate\Support\Facades\Storage;

class ProfilSistemController extends Controller
{
    protected $settingService;

    public function __construct(SettingService $settingService)
    {
        $this->settingService = $settingService;
    }

    public function index()
    {
        // Get all settings to pass to view
        // The view can just use setting('key') helper as well, 
        // but let's be explicit if needed, or just let the view use the helper.
        return view('pengaturan.profil-sistem.index');
    }

    public function update(Request $request)
    {
        $request->validate([
            'nama_peternakan' => 'required|max:100',
            'alamat' => 'required|max:500',
            'no_telp' => 'required|numeric',
            'email' => 'nullable|email',
            'logo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'nama_pemilik' => 'nullable|string|max:100',
            'jabatan_pemilik' => 'nullable|string|max:50',
            'visi_misi' => 'nullable|string|max:1000',
        ]);

        $keys = ['nama_peternakan', 'alamat', 'no_telp', 'email', 'nama_pemilik', 'jabatan_pemilik', 'visi_misi'];
        foreach($keys as $key) {
            $this->settingService->set($key, $request->input($key));
        }

        // Handle logo removal
        if ($request->filled('remove_logo') && $request->input('remove_logo') == '1') {
            $oldLogo = $this->settingService->get('logo_path');
            if ($oldLogo && Storage::disk('public')->exists($oldLogo)) {
                Storage::disk('public')->delete($oldLogo);
            }
            $this->settingService->set('logo_path', null);
        }

        // Handle logo upload
        if ($request->hasFile('logo')) {
            $oldLogo = $this->settingService->get('logo_path');
            if ($oldLogo && Storage::disk('public')->exists($oldLogo)) {
                Storage::disk('public')->delete($oldLogo);
            }
            
            $path = $request->file('logo')->store('logos', 'public');
            $this->settingService->set('logo_path', $path);
        }

        return redirect()->back()->with('success', 'Profil sistem berhasil diperbarui!');
    }
}
