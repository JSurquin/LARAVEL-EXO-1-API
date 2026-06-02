<?php

// Routes API — préfixe automatique /api
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;

// Route publique : login → retourne un token Sanctum
Route::post('/auth/login', [AuthController::class, 'login']);

// Routes protégées par token Bearer (middleware auth:sanctum)
Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('tasks', TaskController::class); // CRUD tasks sécurisé
    Route::post('/auth/logout', [AuthController::class, 'logout']); // Révoque le token
});
