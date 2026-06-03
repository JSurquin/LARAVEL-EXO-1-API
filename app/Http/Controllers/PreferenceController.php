<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

// Préférences utilisateur stockées en session (driver Redis en Exo 4)
class PreferenceController extends Controller
{
    // GET /preferences — affiche le formulaire avec les valeurs session actuelles
    public function index()
    {
        return view('preferences.index', [
            'theme'  => session('theme', 'light'),  // Thème lu depuis la session Redis
            'locale' => session('locale', 'fr'),    // Langue par défaut : français
        ]);
    }

    // POST /preferences — enregistre thème et langue dans la session
    public function store(Request $request)
    {
        $request->validate([
            'theme'  => 'required|in:light,dark',
            'locale' => 'required|in:fr,en',
        ]);

        // session([...]) : persiste les préférences côté serveur (Redis avec SESSION_DRIVER=redis)
        session([
            'theme'  => $request->theme,
            'locale' => $request->locale,
        ]);

        return back()->with('success', 'Préférences enregistrées !');
    }
}
