<?php

use Illuminate\Support\Facades\Route;
use Modules\FixedExpense\Http\Controllers\FixedExpenseApiController;
use Modules\FixedExpense\Http\Controllers\FixedExpenseTemplateApiController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('fixed-expense', FixedExpenseApiController::class);
    Route::apiResource('fixed-expense-template', FixedExpenseTemplateApiController::class);
});
