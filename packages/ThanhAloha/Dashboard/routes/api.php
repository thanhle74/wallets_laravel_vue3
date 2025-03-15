<?php
declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use ThanhAloha\Dashboard\Http\Controllers\DashboardController;

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('dashboard', [DashboardController::class, 'getDashboardStats']);
    Route::get('dashboard/monthly-expenses', [DashboardController::class, 'getMonthlyStats']);
    Route::get('dashboard/top-categories', [DashboardController::class, 'getTopCategories']);
    Route::get('dashboard/recent-transactions', [DashboardController::class, 'getRecentTransactions']);
    Route::get('dashboard/expenses-by-months', [DashboardController::class, 'getRecentTransactions']);
});
