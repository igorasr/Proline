<?php

use App\Http\Controllers\UploadController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('upload')->group(function () {
    Route::post('/', [UploadController::class,'store']);
    Route::get('/', [UploadController::class,'index']);
    Route::get('/{import}', [UploadController::class,'show']);
    Route::post('/{import}/reprocess', [UploadController::class,'reprocess']);
});