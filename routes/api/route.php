<?php

use App\Http\Controllers\Api\RouteCodeController;
use Illuminate\Support\Facades\Route;

Route::prefix('/v1/routes')
    ->name('api.routes.')
    ->group(function () {
        Route::get('/index',[RouteCodeController::class,'getDatatable'])->name('get');
        Route::get('/get/{route}',[RouteCodeController::class,'select'])->name('select');
});

