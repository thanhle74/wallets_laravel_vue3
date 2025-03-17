<?php

use Illuminate\Support\Facades\Route;
use Modules\ModuleMagento\Http\Controllers\ModuleMagentoController;

Route::post('/modulemagento', [ModuleMagentoController::class, 'generate']);
// Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
//     Route::post('/modulemagento', [ModuleMagentoController::class, 'generate']);
// });