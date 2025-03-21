<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PropertyController;
use App\Http\Controllers\PromotionController;
use App\Http\Controllers\SearchController;
use Illuminate\Support\Facades\Route;

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('properties', PropertyController::class);
    Route::post('properties/{property}/promote', [PromotionController::class, 'store']);
    Route::delete('properties/{property}/promote', [PromotionController::class, 'destroy']);
    Route::get('search', [SearchController::class, 'search']);
    Route::get('neighborhood-insights', [SearchController::class, 'neighborhoodInsights']);
   
});