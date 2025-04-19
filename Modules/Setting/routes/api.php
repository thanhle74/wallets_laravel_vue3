<?php
declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\Setting\Http\Controllers\SettingController;

Route::middleware([])->prefix('v1')->group(function () {
    Route::get('/settings', [SettingController::class, 'index']);

    Route::middleware(['auth:sanctum', 'admin'])->group(function () {
        Route::post('/settings/{setting}/upload', [SettingController::class, 'uploadImage']);
        Route::put('/settings', [SettingController::class, 'updateAll']);
        Route::post('/settings', [SettingController::class, 'store']);
    });
});
