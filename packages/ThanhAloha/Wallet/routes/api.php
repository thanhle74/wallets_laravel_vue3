<?php
declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use ThanhAloha\Wallet\Http\Controllers\WalletController;

Route::middleware(['auth:sanctum'])->group(function () {
    Route::apiResource('wallets', WalletController::class);
});
