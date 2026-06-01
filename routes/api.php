<?php

// Routes API — préfixe automatique /api
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

// Enregistre les 5 routes REST : index, store, show, update, destroy
Route::apiResource('tasks', TaskController::class);