<?php

use Illuminate\Support\Facades\Route;
use Modules\FixedExpense\Http\Controllers\FixedExpenseApiController;
use Modules\FixedExpense\Http\Controllers\FixedExpenseTemplateApiController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('fixed-expense', FixedExpenseApiController::class);
    Route::apiResource('fixed-expense-template', FixedExpenseTemplateApiController::class);
    Route::post('fixed-expense-templates/{id}/assign', [FixedExpenseTemplateApiController::class, 'assign']);

    Route::post('fixed-expense/mass-delete', [FixedExpenseApiController::class, 'massDelete']);
    Route::post('fixed-expense-templates/mass-delete', [FixedExpenseTemplateApiController::class, 'massDelete']);
});
