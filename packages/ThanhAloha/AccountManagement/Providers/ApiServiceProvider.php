<?php
declare(strict_types=1);

namespace ThanhAloha\AccountManagement\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class ApiServiceProvider extends ServiceProvider
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
