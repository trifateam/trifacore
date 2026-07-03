<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('app:ping-routes')]
#[Description('Command description')]
class PingRoutes extends Command
{
    /**
     * Execute the console command.
     */
    public function handle()
    {
        $user = \App\Models\Pengguna::find(1);
        if (!$user) {
            $this->error('User ID 1 not found.');
            return;
        }

        auth()->login($user);

        $routes = [
            '/dashboard',
            '/batch',
            '/batch/performa',
            '/batch/riwayat',
            '/kandang',
            '/pencatatan/produksi-telur',
            '/pencatatan/konsumsi-pakan',
            '/pencatatan/konsumsi-vitamin',
            '/pencatatan/deplesi',
            '/pencatatan/suhu',
            '/pencatatan/pupuk',
        ];

        $kernel = app()->make(\Illuminate\Contracts\Http\Kernel::class);

        foreach ($routes as $route) {
            $request = \Illuminate\Http\Request::create($route, 'GET');
            
            // Rebind the session so it doesn't fail
            $request->setLaravelSession(app('session')->driver('array'));
            
            $response = $kernel->handle($request);
            
            $status = $response->getStatusCode();
            if ($status >= 400 && $status !== 404 && $status !== 403) {
                $this->error("[$status] $route");
                // Tampilkan sedikit exception error jika ada
                if (isset($response->exception)) {
                    $this->error($response->exception->getMessage());
                }
            } else {
                $this->info("[$status] $route");
            }
        }
    }
}
