<?php

use App\Http\Controllers\Api\InsertServerController;
use Illuminate\Support\Facades\Route;

Route::prefix('/v1/insert_to_server')
->name('api.insert.server.')
->group(function () {
    Route::post('/',[InsertServerController::class,'store'])->name('insert');
});