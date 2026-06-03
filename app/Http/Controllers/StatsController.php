<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache; // Façade cache — driver configuré via CACHE_STORE (Redis en Exo 4)
use App\Models\User;
use App\Models\Task;

// Statistiques applicatives avec mise en cache Redis (Cache::remember)
class StatsController extends Controller
{
    // GET /stats — affiche le nombre d'utilisateurs et de tâches (mis en cache 1 h)
    public function index()
    {
        // Cache::remember : si la clé 'stats' existe dans Redis, retourne la valeur sans requête SQL
        // Sinon exécute le callback, stocke le résultat 3600 secondes (1 h), puis le retourne
        $stats = Cache::remember('stats', 3600, function () {
            return [
                'users' => User::count(), // Requête SQL uniquement en cas de cache miss
                'tasks' => Task::count(),
            ];
        });

        return view('stats.index', compact('stats'));
    }

    // POST /cache/flush — vide la clé 'stats' du cache (admin uniquement)
    public function flush()
    {
        abort_unless(auth()->user()?->isAdmin(), 403); // Seul l'admin peut vider le cache
        Cache::forget('stats'); // Supprime la clé dans Redis → prochaine visite recompte en BDD
        return redirect()->route('dashboard')->with('success', 'Cache vidé !');
    }
}
