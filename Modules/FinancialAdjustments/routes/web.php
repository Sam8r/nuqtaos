<?php

use Illuminate\Support\Facades\Route;
use Modules\FinancialAdjustments\Http\Controllers\FinancialAdjustmentsController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('financialadjustments', FinancialAdjustmentsController::class)->names('financialadjustments');
});
