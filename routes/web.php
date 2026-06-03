<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\PreferenceController; // Exo 4 — préférences session
use App\Http\Controllers\StatsController;    // Exo 4 — statistiques + cache Redis
use App\Http\Controllers\NewsletterController;

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

// Exo 4 — préférences utilisateur (thème / langue) stockées en session Redis
Route::get('/preferences', [PreferenceController::class, 'index'])->name('preferences.index');
Route::post('/preferences', [PreferenceController::class, 'store'])->name('preferences.store');

// Exo 4 — statistiques avec cache Redis + bouton de vidage (admin)
Route::get('/stats', [StatsController::class, 'index'])->name('stats.index');
Route::post('/cache/flush', [StatsController::class, 'flush'])->name('cache.flush')->middleware('auth');

// Exo 5 — newsletters : liste (GET /newsletters), formulaire (GET /newsletters/create), création (POST /newsletters)
// Limité aux actions index/create/store (pas d'édition ni de suppression)
// Protégé par auth : seul un utilisateur connecté peut créer une newsletter
Route::resource('newsletters', NewsletterController::class)
    ->only(['index', 'create', 'store'])
    ->middleware(['auth']);