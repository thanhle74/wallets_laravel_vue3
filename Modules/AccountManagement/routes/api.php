<?php
declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\AccountManagement\Http\Controllers\AccountManagementController;
use Modules\AccountManagement\Http\Controllers\UserAccountController;

Route::middleware(['auth:sanctum', 'admin'])->prefix('v1')->group(function () {
    Route::apiResource('users', AccountManagementController::class);
});

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::get('is-admin', [UserAccountController::class, 'isAdmin']);
});
