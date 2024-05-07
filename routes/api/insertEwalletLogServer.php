<?php

use App\Http\Controllers\Api\EwalletLogInsertServerController;
use App\Http\Controllers\Api\InsertServerController;
use Illuminate\Support\Facades\Route;

Route::prefix('/v1/insert_ewallet_log_server')
->name('api.insert.e-wallet-log.')
->group(function () {
    Route::post('/',[EwalletLogInsertServerController::class,'store'])->name('to.server');
});