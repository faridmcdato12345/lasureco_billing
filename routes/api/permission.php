<?php

use App\Http\Controllers\Api\PermissionController;
use Illuminate\Support\Facades\Route;

Route::prefix('/v1/permission')
    ->name('api.permission.')
    ->group(function () {
        Route::get('/',[PermissionController::class,'index'])->name('index');
});
