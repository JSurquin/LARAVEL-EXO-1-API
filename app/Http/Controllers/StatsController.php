<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\User;
use Illuminate\Support\Facades\Cache;

class StatsController extends Controller
{

    public function index()
{
    $stats = Cache::remember('stats', 3600, function () {
        return [
            'users' => User::count(),
            'tasks' => Task::count(),
        ];
    });

    return view('stats.index', compact('stats'));
}
    public function flush()
{
    abort_unless(auth()->user()?->isAdmin(), 403);
    Cache::forget('stats');
    return redirect()->route('dashboard')->with('success', 'Cache vidé !');
}
}
