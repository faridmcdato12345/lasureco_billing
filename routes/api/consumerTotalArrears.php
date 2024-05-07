<?php

use App\Http\Controllers\Api\ConsumerController;
use App\Http\Controllers\Api\showConsumerTotalArrearsController;
use Illuminate\Support\Facades\Route;


Route::prefix('/v1/consumer')
->name('api.consumers.')
->group(function () {
    Route::post('/arrears',[ShowConsumerTotalArrearsController::class,'consumerTotalArrears'])->name('arrears');
});


