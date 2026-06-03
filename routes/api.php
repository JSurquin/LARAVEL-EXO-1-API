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


// Exo 6 — route tasks SANS auth:sanctum pour les tests Feature Pest (TaskApiTest)
// En production réelle, seule la route du groupe auth:sanctum ci-dessus doit être utilisée
Route::apiResource('tasks', TaskController::class);
