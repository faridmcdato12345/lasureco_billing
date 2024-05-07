<?php

use App\Http\Controllers\Api\PostingController;
use Illuminate\Support\Facades\Route;


Route::prefix('/v1/post_collection')
->name('api.posting.')
->group(function () {
    Route::get('/index',[PostingController::class,'index'])->name('index');
    Route::post('/update_ar',[PostingController::class,'addAcknowledgementReceipt'])->name('update.ar');
});