<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PreferenceController extends Controller
{

public function index()
{
    return view('preferences.index', [
        'theme'  => session('theme', 'light'),
        'locale' => session('locale', 'fr'),
    ]);
}

public function store(Request $request)
{
    $request->validate([
        'theme'  => 'required|in:light,dark',
        'locale' => 'required|in:fr,en',
    ]);

    session([
        'theme'  => $request->theme,
        'locale' => $request->locale,
    ]);

    return back()->with('success', 'Préférences enregistrées !');
}

}
