<?php

use App\Http\Controllers\Api\GcashConsumerController;
use Illuminate\Support\Facades\Route;

Route::prefix('/v1/gcash/consumer/')
    ->name('api.gcash.')
    ->group(function () {
        Route::post('/validate', [GcashConsumerController::class, 'consumerInquiry'])->name('consumer.validate');
        Route::post('/payment', [GcashConsumerController::class, 'gcashPayment'])->name('consumer.payment');
});