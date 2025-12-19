<?php

use Illuminate\Support\Facades\Route;
use Modules\Quotations\Http\Controllers\QuotationsController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('quotations', QuotationsController::class)->names('quotations');
});
