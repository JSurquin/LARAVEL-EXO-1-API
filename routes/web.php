<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\PreferenceController;
use App\Http\Controllers\StatsController;

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

Route::get('/preferences', [PreferenceController::class, 'index'])->name('preferences.index');
Route::post('/preferences', [PreferenceController::class, 'store'])->name('preferences.store');

Route::get('/stats', [StatsController::class, 'index'])->name('stats.index');
Route::post('/cache/flush', [StatsController::class, 'flush'])->name('cache.flush')->middleware('auth');