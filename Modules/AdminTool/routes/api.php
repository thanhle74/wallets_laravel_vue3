<?php

use Illuminate\Support\Facades\Route;
use Modules\AdminTool\Http\Controllers\AdminToolController;

Route::middleware('auth:sanctum')->prefix('v1')->group(function () {
    Route::post('/admin-run-command', [AdminToolController::class, 'runCommand']);
});
