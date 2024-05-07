<?php

use App\Http\Controllers\Api\ConsumerController;
use App\Http\Controllers\Api\GetConsumerPrevReadingController;
use Illuminate\Support\Facades\Route;


Route::prefix('/v1/get/consumer/prev_reading')
->name('api.get_consumer_prev_reading.')
->group(function () {
    Route::post('/',[GetConsumerPrevReadingController::class,'getPrevReading'])->name('index');
});


