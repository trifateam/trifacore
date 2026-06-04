<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * Menerima parameter role (bisa multiple), contoh: role:Admin,Owner
     * Cek auth()->user()->role terhadap daftar role yang diizinkan.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  ...$roles  Daftar role yang diizinkan
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        // Jika user tidak login, redirect ke login
        if (!$user) {
            return redirect()->route('login');
        }

        // Cek apakah role user termasuk dalam daftar role yang diizinkan
        if (!in_array($user->role, $roles)) {
            return redirect()
                ->route('dashboard')
                ->with('error', 'Anda tidak memiliki akses ke halaman ini');
        }

        return $next($request);
    }
}
