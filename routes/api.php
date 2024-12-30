<?php

use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::apiResource('/posts', App\Http\Controllers\Api\PostController::class);
Route::apiResource('/master-items', App\Http\Controllers\Api\MasterItemController::class);
Route::apiResource('/transactions', App\Http\Controllers\Api\TransactionController::class);

//auth
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
