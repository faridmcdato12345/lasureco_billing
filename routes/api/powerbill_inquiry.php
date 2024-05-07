<?php

use App\Http\Controllers\Api\PowerBillInquiryController;
use Illuminate\Support\Facades\Route;

Route::prefix('/v1/inquiry')
    ->name('api.inquiry.')
    ->group(function () {
        Route::post('/power_bill', [PowerBillInquiryController::class, 'inquiry'])->name('powerbill');
        Route::post('/power_bill2', [PowerBillInquiryController::class, 'inquiry2'])->name('powerbill2');
});