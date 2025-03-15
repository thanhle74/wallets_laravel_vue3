<?php
declare(strict_types=1);

use ThanhAloha\AccountManagement\Http\Controllers\AccountManagementApiController;
use ThanhAloha\AccountManagement\Http\Controllers\UserAccountController;

Route::middleware(['auth:sanctum', 'admin'])->group(function () {
    Route::apiResource('users', AccountManagementApiController::class);
});

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('is-admin', [UserAccountController::class, 'isAdmin']);
});
