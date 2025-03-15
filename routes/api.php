<?php
declare(strict_types=1);

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\CategoryApi;
use App\Http\Controllers\Api\TransactionApi;
use App\Http\Controllers\Api\AuthController;

Route::prefix('v1')->group(function () {
    Route::post('login', [AuthController::class, 'login']);

    Route::middleware(['auth:sanctum'])->group(function () {
        Route::apiResource('categories', CategoryApi::class);
        Route::apiResource('transactions', TransactionApi::class);

        Route::post('logout', [AuthController::class, 'logout']);
        Route::get('auth/check', function (Request $request) {
            return response()->json(['authenticated' => true]);
        });
    });
});
