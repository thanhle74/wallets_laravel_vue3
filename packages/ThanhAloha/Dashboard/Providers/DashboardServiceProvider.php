<?php
declare(strict_types=1);

namespace ThanhAloha\Dashboard\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;

class DashboardServiceProvider extends ServiceProvider
{
    public function register()
    {
    }

    public function boot(): void
    {
        Route::prefix('api/v1')->group(function () {
            $this->loadRoutesFrom(__DIR__ . '/../routes/api.php');
        });
    }
}
