<?php
declare(strict_types=1);

namespace ThanhAloha\Wallet\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;

class WalletServiceProvider extends ServiceProvider
{
    public function register()
    {
    }

    public function boot(): void
    {
        Route::model('wallet', \ThanhAloha\Wallet\Models\Wallet::class);
        Route::prefix('api/v1')->group(function () {
            $this->loadRoutesFrom(__DIR__ . '/../routes/api.php');
        });
    }
}
