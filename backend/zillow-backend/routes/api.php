<?php

use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Public Routes (no CSRF)
Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

// Protected Routes (Sanctum with CSRF if stateful)
Route::middleware('sanctum')->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);
    Route::get('user', function (Request $request) {
        return $request->user()->load('roles');
    })->middleware('auth:sanctum'); // Ensure auth
});
