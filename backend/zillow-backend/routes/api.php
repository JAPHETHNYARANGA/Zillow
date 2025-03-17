<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Public Authentication Routes
Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

// Protected Routes (require Sanctum authentication)
Route::middleware('auth:sanctum')->group(function () {
    // Authentication
    Route::post('logout', [AuthController::class, 'logout']);

    // User Management
    Route::get('user', function (Request $request) {
        return $request->user()->load('roles'); // Return authenticated user with roles
    });
    Route::get('profile', [UserController::class, 'profile']);
    Route::resource('users', UserController::class)->only(['show', 'update', 'destroy']);

    // Admin Routes
    Route::prefix('admin')->group(function () {
        Route::get('users', [AdminController::class, 'getUsers']);
        Route::get('properties', [AdminController::class, 'getProperties']);
        Route::delete('users/{user}', [AdminController::class, 'deleteUser']);
        Route::delete('properties/{property}', [AdminController::class, 'deleteProperty']);
    });
});