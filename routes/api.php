<?php
declare(strict_types=1);

use Illuminate\Support\Facades\Route;

//use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\CategoryApi;
use App\Http\Controllers\Api\AuthManagementApi;
use App\Http\Controllers\Api\WalletApi;
use App\Http\Controllers\Api\TransactionApi;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DashboardControllerApi;

Route::prefix('v1')->group(function () {
    Route::post('login', [AuthController::class, 'login']);

    Route::middleware(['auth:sanctum'])->group(function () {
        Route::apiResource('categories', CategoryApi::class);
        Route::apiResource('wallets', WalletApi::class);
        Route::apiResource('transactions', TransactionApi::class);

        Route::post('logout', [AuthController::class, 'logout']);
        Route::get('is-admin', [AuthManagementApi::class, 'isAdmin']);
        Route::get('dashboard', [DashboardControllerApi::class, 'getDashboardStats']);
        Route::get('auth/check', function (Request $request) {
            return response()->json(['authenticated' => true]);
        });
    });

//    Route::middleware(['auth:sanctum', 'admin'])->group(function () {
//        Route::apiResources([
//            'users' => UserController::class,
//            'currencies' => CurrencyController::class,
//        ]);
//    });
});
