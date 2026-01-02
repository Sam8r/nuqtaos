<?php

use Illuminate\Support\Facades\Route;
use Modules\FinancialAdjustments\Http\Controllers\FinancialAdjustmentsController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('financialadjustments', FinancialAdjustmentsController::class)->names('financialadjustments');
});
