<?php

use App\Http\Controllers\Api\UsersController;
use App\Http\Controllers\Api\AuthController;
use Illuminate\Support\Facades\Route;

Route::prefix('/v1/user')
    ->name('api.users.')
    ->group(function () {
        Route::get('/index',[UsersController::class,'index'])->name('index');
        Route::post('/registration',[AuthController::class,'register'])->name('register');
        //Route::post('login',[AuthController::class,'login'])->name('login');
        Route::patch('/change_pass/{user}',[UsersController::class,'userChangePass'])->name('change.pass');
        Route::patch('/change_status/{user}',[UsersController::class,'updateStatus'])->name('update.status');
        Route::patch('/update_password/{user}',[UsersController::class,'update'])->name('update.password');
        Route::get('/all_user',[UsersController::class,'allUsers'])->name('all');
        Route::post('/add_user_role',[UsersController::class,'addUserRole'])->name('add_user_role');
        Route::post('/remove_user_role',[UsersController::class,'removeUserRole'])->name('remove_user_role');
});
Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('v1/user/logout',[AuthController::class,'logout']);
});

