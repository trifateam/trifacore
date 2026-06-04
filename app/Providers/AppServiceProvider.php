<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        /*
        |----------------------------------------------------------------------
        | Custom Blade Directive: @role / @endrole
        |----------------------------------------------------------------------
        |
        | Penggunaan:
        |   @role('Admin')           — satu role
        |   @role('Admin', 'Owner')  — multiple role
        |
        */
        Blade::if('role', function (string ...$roles) {
            return auth()->check() && in_array(auth()->user()->role, $roles);
        });
    }
}
