<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Exo 4 — log chaque requête SQL dans storage/logs/laravel.log
        // Permet de vérifier : 0 requête si cache hit, 2 requêtes (COUNT users + tasks) si cache miss
        \DB::listen(fn($q) => logger($q->sql));
    }
}
