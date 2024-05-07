<?php
use App\Http\Controllers\UploadCollectionToServerController;
use Illuminate\Support\Facades\Route;

Route::prefix('/v1/upload_collect_server')
->name('api.upload_collection_server.')
->group(function () {
    Route::post('/get_data',[UploadCollectionToServerController::class,'getCollection'])->name('get');
    Route::post('/update_local_db',[UploadCollectionToServerController::class,'updateLocalDb'])->name('update.local.db');
});