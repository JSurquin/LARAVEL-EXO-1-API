<?php

namespace Tests\Browser\Pages;

use Laravel\Dusk\Browser; // Type du navigateur passé aux méthodes assert() et elements()

// Page Object pour la page d'accueil — encapsule URL, assertions et sélecteurs
class HomePage extends Page
{
    /**
     * URL relative de la page (utilisée par $browser->on(new HomePage)).
     */
    public function url(): string
    {
        return '/'; // Route racine — page welcome Laravel
    }

    /**
     * Assertions spécifiques à cette page (ex. : vérifier un titre, un bouton).
     * Laissé vide ici — à compléter selon les besoins du projet.
     */
    public function assert(Browser $browser): void
    {
        //
    }

    /**
     * Raccourcis d'éléments propres à la page d'accueil.
     *
     * @return array<string, string>
     */
    public function elements(): array
    {
        return [
            '@element' => '#selector', // Exemple : sélecteur CSS d'un élément de la home
        ];
    }
}
