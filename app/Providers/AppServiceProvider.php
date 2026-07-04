<?php

namespace App\Providers;

use App\Services\TaskNotificationService;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\View;
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

        /*
        |----------------------------------------------------------------------
        | Custom Blade Directive: @rupiah
        |----------------------------------------------------------------------
        */
        Blade::directive('rupiah', function ($expression) {
            return "<?php echo \App\Helpers\RupiahFormatter::format($expression); ?>";
        });
        /*
        |----------------------------------------------------------------------
        | View Composer untuk Badge Notification Harian (Pegawai Kandang)
        |----------------------------------------------------------------------
        */
        View::composer('*', function ($view) {
            if (auth()->check() && auth()->user()->hasRole('Pegawai Kandang')) {
                // Menghindari duplikasi query jika dipanggil berkali-kali di view yang sama
                static $tasks = null;
                if ($tasks === null) {
                    $tasks = TaskNotificationService::getUncompletedTasks();
                }
                $view->with('uncompletedTasks', $tasks);
            }
        });
    }
}
