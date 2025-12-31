<?php

use Illuminate\Support\Facades\Route;
use Modules\Leaves\Http\Controllers\LeavesController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('leaves', LeavesController::class)->names('leaves');
});
