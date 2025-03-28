<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PropertyController;

use App\Http\Controllers\SearchController;
use App\Http\Controllers\ValuationController;
use Illuminate\Support\Facades\Route;

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->group(function () {
    
 
    Route::get('search', [SearchController::class, 'search']);
    Route::get('neighborhood-insights', [SearchController::class, 'neighborhoodInsights']);
    Route::apiResource('valuations', ValuationController::class);
    Route::apiResource('properties', PropertyController::class);
});

Route::get('fetch_properties', [PropertyController::class,'fetchProperties']);
Route::get('fetch_property/{id}', [PropertyController::class,'fetchIndividualProperty']);
