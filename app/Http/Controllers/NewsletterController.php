<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Jobs\SendNewsletterJob;  // Job asynchrone qui envoie la newsletter via la queue
use App\Models\Newsletter;       // Modèle Eloquent de la newsletter

// Contrôleur gérant l'affichage et la création des newsletters (Exo 5)
class NewsletterController extends Controller
{
    /**
     * Affiche la liste de toutes les newsletters, triées de la plus récente à la plus ancienne.
     */
    public function index()
    {
        return view('newsletters.index', [
            'newsletters' => Newsletter::latest()->get(), // Récupère toutes les newsletters triées par created_at DESC
        ]);
    }

    /**
     * Affiche le formulaire de création d'une nouvelle newsletter.
     */
    public function create()
    {
        return view('newsletters.create');
    }

    /**
     * Valide les données du formulaire, crée la newsletter en base,
     * puis dispatche le job d'envoi en file d'attente Redis/Horizon.
     */
    public function store(Request $request)
    {
        // Validation : subject obligatoire (max 255 caractères), body obligatoire
        $validated = $request->validate([
            'subject' => 'required|string|max:255',
            'body'    => 'required|string',
        ]);

        // Crée le modèle Newsletter en base avec subject et body (sent_at reste null)
        $newsletter = Newsletter::create($validated);

        // Dispatche le job en queue — l'envoi réel se fait dans le worker (php artisan queue:work)
        // On passe l'admin connecté pour qu'il reçoive la notification de confirmation
        SendNewsletterJob::dispatch($newsletter, auth()->user());

        // Redirige vers la liste avec un message flash de succès
        return redirect()->route('newsletters.index')
            ->with('success', 'Newsletter en file d\'attente — rechargez la liste pour voir « Envoyée ».');
    }
}
