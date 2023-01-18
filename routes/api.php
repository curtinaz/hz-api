<?php

use App\Http\Controllers\UsersController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'auth'], function () {
    Route::post('login', [UsersController::class, 'login']);
    Route::post('register', [UsersController::class, 'register']);
});

Route::group(['prefix' => 'user'], function () {
    Route::get('me', [UsersController::class, 'me'])->middleware('auth:sanctum');
});
