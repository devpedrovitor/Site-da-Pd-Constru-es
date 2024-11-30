<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;

Route::middleware('guest:sanctum')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);

    Route::post('/register', [UserController::class, 'create']);

    Route::get('/categories', [CategoryController::class, 'index']);

    Route::get('/products', [ProductController::class, 'index']);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::delete('/logout', [AuthController::class, 'logout']);
});
