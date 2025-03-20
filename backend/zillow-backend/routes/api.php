<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PropertyController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Public Routes (No CSRF required)
Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

// Protected Routes (Require Authentication)
Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);

    // Fetch authenticated user with roles
    Route::get('user', function (Request $request) {
        return $request->user()->load('roles');
    });

    // Property resource routes
    Route::apiResource('properties', PropertyController::class);
});
