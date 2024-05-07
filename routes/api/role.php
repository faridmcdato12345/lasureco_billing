<?php

use App\Http\Controllers\Api\RoleController;
use Illuminate\Support\Facades\Route;

Route::prefix('/v1/role')
    ->name('api.roles.')
    ->group(function () {
        Route::get('/add_user_role',[RoleController::class,'addUserRole'])->name('add_user_role');
        Route::get('/',[RoleController::class,'index'])->name('index');
        Route::get('/{role}',[RoleController::class,'show'])->name('show');    
});
