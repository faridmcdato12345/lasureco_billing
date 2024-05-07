<?php

use App\Http\Controllers\Api\ConsumerTypeController;
use App\Http\Controllers\Api\RouteCodeController;
use Illuminate\Support\Facades\Route;

Route::prefix('/v1/consumer_type')
    ->name('api.consumerType.')
    ->group(function () {
        Route::get('v1/consumer_type', [ConsumerTypeController::class, 'index'])->name('index');
        Route::patch('v1/consumer_type/{consumerType}', [ConsumerTypeController::class, 'update'])->name('update');
        Route::post('v1/consumer_type', [ConsumerTypeController::class, 'store'])->name('store');
        Route::get('/',[ConsumerTypeController::class,'getDatatable'])->name('get');
        Route::get('/index',[ConsumerTypeController::class,'getDatatableIndex'])->name('get.datatable');
        Route::get('/get/{consumerType}',[ConsumerTypeController::class,'select'])->name('select');
        Route::get('/withoutpaginate',[ConsumerTypeController::class,'consumerTypeWithoutPaginate'])->name('withoutpaginate');
});

