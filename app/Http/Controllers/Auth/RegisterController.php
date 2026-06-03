<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Pengguna;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    /**
     * Tampilkan form registrasi.
     */
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    /**
     * Proses registrasi pengguna baru.
     */
    public function register(Request $request)
    {
        $request->validate([
            'nama_lengkap' => ['nullable', 'string', 'max:100'],
            'username'     => ['required', 'string', 'max:50', 'unique:pengguna,username'],
            'password'     => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $pengguna = Pengguna::create([
            'nama_lengkap' => $request->nama_lengkap,
            'username'     => $request->username,
            'password'     => Hash::make($request->password),
            'role'         => 'Pegawai Kandang',
        ]);

        Auth::login($pengguna);

        return redirect('/dashboard');
    }
}
