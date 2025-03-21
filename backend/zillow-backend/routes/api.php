<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PropertyController;
use App\Http\Controllers\SearchController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Public Routes
Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

// Protected Routes
Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);
    Route::get('user', function (Request $request) {
        return $request->user()->load('roles');
    });
    Route::apiResource('properties', PropertyController::class);
    Route::post('properties/{property}/promote', [PropertyController::class, 'promote']); // New route
    Route::get('search', [SearchController::class, 'search']);
    Route::get('neighborhood-insights', [SearchController::class, 'neighborhoodInsights']);
});