<?php
declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\AccountManagement\Http\Controllers\AccountManagementController;
use Modules\AccountManagement\Http\Controllers\UserAccountController;
use Modules\AccountManagement\Http\Controllers\AuthController;

Route::prefix('v1')->group(function () {
    Route::post('login', [AuthController::class, 'login']);

    Route::middleware(['auth:sanctum'])->group(function () {
        Route::post('logout', [AuthController::class, 'logout']);
        Route::get('is-admin', [UserAccountController::class, 'isAdmin']);
        Route::get('auth/check', function () {
            return response()->json(['authenticated' => true]);
        });

        Route::middleware(['admin'])->group(function () {
            Route::apiResource('users', AccountManagementController::class);
        });
    });
});
