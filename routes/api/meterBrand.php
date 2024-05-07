<?php
use App\Http\Controllers\Api\MeterBrandController;
use Illuminate\Support\Facades\Route;

Route::prefix('/v1/meter_brand')
->name('api.meter_brand.')
->group(function () {
    Route::get('/get_data',[MeterBrandController::class,'getMeterBrand'])->name('get');
    Route::get('/', [MeterBrandController::class,'index'])->name('index');
    Route::post('/store', [MeterBrandController::class,'store'])->name('store');
    Route::patch('/{id}', [MeterBrandController::class,'update'])->name('update');
    Route::delete('/{id}', [MeterBrandController::class,'destroy'])->name('delete');
    Route::get('/{request}', [MeterBrandController::class,'search'])->name('search');
    Route::get('/get/{meterBrand}',[MeterBrandController::class,'select'])->name('select');
});
