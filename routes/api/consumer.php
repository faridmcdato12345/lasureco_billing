<?php

use App\Http\Controllers\Api\ConsumerController;
use Illuminate\Support\Facades\Route;


Route::prefix('/v1/consumer')
->name('api.consumers.')
->group(function () {
    Route::post('/index/{id}',[ConsumerController::class,'datatableConsumer'])->name('get');
    Route::get('/main_account',[ConsumerController::class,'mainAccount'])->name('main.account');
    Route::get('/get/{consumer}',[ConsumerController::class,'select'])->name('select');
    Route::patch('/status/update/{consumer}',[ConsumerController::class,'updateStatus'])->name('update.status');
    Route::post('/store/image/',[ConsumerController::class,'uploadeImage'])->name('update.image');
    Route::patch('/meter_change/{meterMaster}',[ConsumerController::class,'meterChange'])->name('meter.change');
    Route::patch('/change_name/{consumer}',[ConsumerController::class,'changeName'])->name('change.name');
    Route::patch('/change_type/{consumer}',[ConsumerController::class,'changeType'])->name('change.type');
    Route::get('/active_consumers',[ConsumerController::class,'getActiveConsumers'])->name('active');
    Route::get('/inactive_consumers',[ConsumerController::class,'getInActiveConsumers'])->name('inactive');
    Route::patch('/modify/{consumer}',[ConsumerController::class,'modify'])->name('modify');
	Route::post('/full_name',[ConsumerController::class,'getConsumerNames'])->name('full_name');
	Route::post('/account_no',[ConsumerController::class,'getConsumerAccountNo'])->name('account_no');
	Route::post('/input_validation',[ConsumerController::class,'checkConsumerInputValidation'])->name('input.validation');
	Route::patch('/add_consumer_meter/{consumer}',[ConsumerController::class,'addConsumerMeter'])->name('add.meter');
    Route::get('/multiplier/{id}',[ConsumerController::class,'getConsumerMult'])->name('multiplier');
    Route::patch('/multiplier/update/{consumer}',[ConsumerController::class,'changeConsMult'])->name('update.multiplier');
});


