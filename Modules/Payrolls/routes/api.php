<?php

use Illuminate\Support\Facades\Route;
use Modules\Payrolls\Http\Controllers\PayrollsController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('payrolls', PayrollsController::class)->names('payrolls');
});
