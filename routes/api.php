<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\SerieController;
use App\Http\Controllers\Api\MovieController;

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('login', [AuthController::class, 'login'])->name('login');
Route::post('register', [AuthController::class, 'register'])->name('register');

Route::middleware('auth:api')->group(function() {
    Route::get('logout', [AuthController::class, 'logout']);

    Route::resource('series', SerieController::class);
    Route::resource('categories', CategoryController::class);
    Route::resource('movies', MovieController::class);
});