<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/components-demo', fn() => view('components-demo'));

// Dashboard — accessible uniquement aux utilisateurs connectés (session Fortify)
Route::middleware(['auth'])->get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

// CRUD articles — toutes les routes posts protégées par middleware auth
Route::middleware('auth')->group(function () {
    Route::resource('posts', PostController::class);
});
