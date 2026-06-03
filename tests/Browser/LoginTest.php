<?php

use App\Models\User;                                      // Modèle utilisateur — factory pour créer un compte de test
use Illuminate\Foundation\Testing\DatabaseMigrations;     // Réinitialise la BDD avant chaque test Dusk (SQLite fichier)
use Laravel\Dusk\Browser;                                 // API fluent Dusk : visit(), type(), press(), assertPathIs()

// Applique DatabaseMigrations à ce fichier (alternative à RefreshDatabase pour Dusk)
uses(DatabaseMigrations::class);

// Test Pest : vérifie le flux de connexion Fortify via le navigateur
it('user can login via Fortify', function () {
    // Crée un utilisateur en base avec le mot de passe en clair 'password' (Fortify le hashe au login)
    $user = User::factory()->create(['password' => 'password']);

    // $this->browse() ouvre une session Chrome et exécute les actions dans le callback
    $this->browse(function (Browser $browser) use ($user) {
        $browser->visit('/login')                    // Navigue vers le formulaire Fortify
            ->type('email', $user->email)            // Remplit le champ email (attribut name="email")
            ->type('password', 'password')            // Remplit le champ password
            ->press('Se connecter')                   // Clique le bouton dont le texte est « Se connecter »
            ->assertPathIs('/dashboard');             // Vérifie la redirection vers /dashboard après auth réussie
    });
});
