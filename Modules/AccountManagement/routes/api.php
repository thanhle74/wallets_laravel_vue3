<?php

use Illuminate\Support\Facades\Route;
use Modules\AccountManagement\Http\Controllers\AccountManagementController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('accountmanagement', AccountManagementController::class)->names('accountmanagement');
});
