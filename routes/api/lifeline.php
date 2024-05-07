<?php
use App\Http\Controllers\Api\LifeLineRatesController;
use Illuminate\Support\Facades\Route;


Route::prefix('/v1/lifeline')
->name('api.lifeline.')
->group(function () {
    Route::get('/index',[LifeLineRatesController::class,'getDataTable'])->name('get');
    Route::get('v1/lifeline_rates/edit/{lifeLine}', [LifeLineRatesController::class, 'edit'])->name('edit');
    Route::get('v1/lifeline_rates', [LifeLineRatesController::class, 'index']);
    Route::post('v1/lifeline_rates', [LifeLineRatesController::class, 'store'])->name('store');
    Route::patch('v1/lifeline_rates/{id}', [LifeLineRatesController::class, 'update'])->name('update');
    Route::delete('v1/lifeline_rates/{id}', [LifeLineRatesController::class, 'destroy']);
});