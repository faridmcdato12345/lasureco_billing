<?php

use App\Http\Controllers\Api\SalesController;
use Illuminate\Support\Facades\Route;


Route::prefix('/v1/sales')
->name('api.sales.')
->group(function () {
    Route::get('/this_year_sales',[SalesController::class,'getThisYearSale'])->name('this.year');
    Route::get('/last_year_sales',[SalesController::class,'getLastYearSale'])->name('last.year');
    Route::get('/total_sales',[SalesController::class,'totalSales'])->name('total');
});


