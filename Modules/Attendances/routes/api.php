<?php

use Illuminate\Support\Facades\Route;
use Modules\Attendances\Http\Controllers\AttendancesController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('attendances', AttendancesController::class)->names('attendances');
});
