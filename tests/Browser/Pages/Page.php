<?php

namespace Tests\Browser\Pages;

use Laravel\Dusk\Page as BasePage; // Classe de base pour les Page Objects Dusk (réutilisables entre tests)

// Page Object abstrait — définit des raccourcis CSS réutilisables (@element) pour tous les tests Browser
abstract class Page extends BasePage
{
    /**
     * Raccourcis d'éléments globaux du site.
     * Usage dans un test : $browser->click('@element') au lieu de $browser->click('#selector').
     *
     * @return array<string, string>
     */
    public static function siteElements(): array
    {
        return [
            '@element' => '#selector', // Exemple : remplacer par un vrai sélecteur CSS du projet
        ];
    }
}
