<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BucketController;
use App\Http\Middleware\BucketAuth;
use App\Http\Middleware\CorsMiddleware;

Route::get('/', function () {
    return response()->json(['message' => 'ok'], 200);
});

Route::post('bucket/upload/{path}', [BucketController::class, 'upload'])->middleware(BucketAuth::class)->middleware(CorsMiddleware::class);
Route::get('bucket/download/{file}/{path}', [BucketController::class, 'download'])->middleware(BucketAuth::class)->middleware(CorsMiddleware::class);
Route::get('bucket/files/{path}', [BucketController::class, 'files'])->middleware(BucketAuth::class)->middleware(CorsMiddleware::class);
Route::delete('bucket/delete/{file}/{path}', [BucketController::class, 'delete'])->middleware(BucketAuth::class)->middleware(CorsMiddleware::class);