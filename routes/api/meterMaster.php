<?php
use App\Http\Controllers\Api\MeterMasterController;
use App\Http\Controllers\Api\ConsumerController;
use Illuminate\Support\Facades\Route;

Route::prefix('/v1/meter/master')
    ->name('api.meter.master.')
    ->group(function () {
        Route::get('/',[MeterMasterController::class,'getDatatable'])->name('get');
        Route::get('/get/{meterMaster}',[MeterMasterController::class,'select'])->name('select');
});

Route::prefix('/v1/consumer/history')
    ->name('api.cons.master.')
    ->group(function () {
        Route::get('/consumer/{cmId}',[ConsumerController::class,'consHistoryLog'])->name('get');
});