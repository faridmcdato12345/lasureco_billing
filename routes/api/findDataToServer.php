<?php

use App\Http\Controllers\Api\FindDataFromServerController;
use Illuminate\Support\Facades\Route;


Route::prefix('/v1/find_data_to_server')
->name('api.server.find.')
->group(function () {
    Route::post('/',[FindDataFromServerController::class,'findSale'])->name('get');
});


