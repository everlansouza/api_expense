<?php

use App\Http\Controllers\Api\V1\ExpensesController;
use App\Http\Controllers\Api\V1\UserController;
use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::post('/login', [AuthController::class, 'login']);

Route::post('/users/create', [UserController::class, 'store']);

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('expenses', ExpensesController::class);

    Route::apiResource('users', UserController::class);

    Route::post('/logout', [AuthController::class, 'logout']);
});
