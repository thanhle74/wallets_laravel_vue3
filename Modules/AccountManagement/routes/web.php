<?php

use Illuminate\Support\Facades\Route;
use Modules\AccountManagement\Http\Controllers\AccountManagementController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('accountmanagement', AccountManagementController::class)->names('accountmanagement');
});
