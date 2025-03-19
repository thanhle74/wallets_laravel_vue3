<?php

use Illuminate\Support\Facades\Route;
use Modules\AdminTool\Http\Controllers\AdminToolController;
use Modules\AdminTool\Http\Controllers\ModuleManagerController;

Route::middleware(['auth:sanctum', 'admin'])->prefix('v1')->group(function () {
    Route::post('/admin-run-command', [AdminToolController::class, 'runCommand']);
    Route::get('/admin-get-factories', [AdminToolController::class, 'getFactories']);
    Route::get('/admin-get-seeders', [AdminToolController::class, 'getSeeders']);
    Route::post('/admin-run-factory', [AdminToolController::class, 'runFactory']);
    Route::post('/admin-run-seeder', [AdminToolController::class, 'runSeeder']);

    Route::get('/admin-list-modules', [ModuleManagerController::class, 'listModules']);
    Route::post('/admin-run-module-command', [ModuleManagerController::class, 'runModuleCommand']);
    Route::post('/admin-create-module', [ModuleManagerController::class, 'createModule']);
});
