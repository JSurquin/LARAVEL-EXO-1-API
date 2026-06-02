<?php

// Routes API — préfixe automatique /api
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;

// Public : login → retourne un token
Route::post('/auth/login', [AuthController::class, 'login']);

// Protégées par token Bearer
Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('tasks', TaskController::class);
    Route::post('/auth/logout', [AuthController::class, 'logout']);
});