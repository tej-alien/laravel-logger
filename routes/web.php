<?php

use Illuminate\Support\Facades\Route;

// Catch all route
Route::get('/{view?}', \App\Http\Controllers\IndexController::class)
    ->where('view', '(.*)')
    ->name('log-viewer.index');
