<?php

use Illuminate\Support\Facades\Route;
use Modules\Payrolls\Http\Controllers\PayrollsController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('payrolls', PayrollsController::class)->names('payrolls');
});
