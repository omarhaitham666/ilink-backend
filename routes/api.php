<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PostController;

    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/me', [AuthController::class, 'me']);
    });



    Route::middleware('auth:sanctum')->group(function () {

    Route::prefix('posts')->group(function () {

        Route::post('/', [PostController::class, 'store']);

        Route::put('/{post}', [PostController::class, 'update']);

        Route::delete('/{post}', [PostController::class, 'destroy']);

    });
});