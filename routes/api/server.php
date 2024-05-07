<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ServerIpAddressController;


Route::prefix('/v1/server')
->name('api.servers.')
->group(function () {
    Route::get('/get',[ServerIpAddressController::class,'datatableServer'])->name('get');
    Route::delete('/delete/{server}',[ServerIpAddressController::class,'delete'])->name('delete');
    Route::get('/check',[ServerIpAddressController::class,'checkServerIp'])->name('check');
    Route::patch('/update/{server}',[ServerIpAddressController::class,'update'])->name('update');
    Route::post('/',[ServerIpAddressController::class,'store'])->name('store');
    Route::get('/show/{server}',[ServerIpAddressController::class,'show'])->name('show');
    Route::get('/get_api',[ServerIpAddressController::class,'getIp'])->name('get.ip');
});


