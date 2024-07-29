<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BucketController;
use App\Http\Middleware\BucketAuth;

Route::get('/', function () {
    return response()->json(['message' => 'ok'], 200);
});

Route::post('bucket/upload', [BucketController::class, 'upload'])->middleware(BucketAuth::class);
Route::get('bucket/download/{file}', [BucketController::class, 'download'])->middleware(BucketAuth::class);
Route::get('bucket/files', [BucketController::class, 'files'])->middleware(BucketAuth::class);
Route::delete('bucket/delete/{file}', [BucketController::class, 'delete'])->middleware(BucketAuth::class);