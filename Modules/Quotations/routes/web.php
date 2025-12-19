<?php

use Illuminate\Support\Facades\Route;
use Modules\Quotations\Http\Controllers\QuotationsController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('quotations', QuotationsController::class)->names('quotations');
});
