<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\RolePermissionController;

Route::prefix('/v1/user')
    ->name('api.rolePermission.')
    ->group(function () {
        Route::patch('role_permission/{role}',[RolePermissionController::class,'update'])->name('update');
        Route::post('role_permission',[RolePermissionController::class,'store'])->name('store');
});
