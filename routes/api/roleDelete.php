<?php

use App\Http\Controllers\Api\ConsumerController;
use App\Http\Controllers\Api\RoleDeleteController;
use Illuminate\Support\Facades\Route;


Route::prefix('/v1/role')
->name('api.role.')
->group(function () {
    Route::post('/delete',[RoleDeleteController::class,'delete'])->name('delete');
});


