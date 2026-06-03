<?php

use Laravel\Dusk\Browser; // API fluent Dusk pour piloter Chrome

// Test Pest minimal : vérifie que la page d'accueil affiche le mot « Laravel »
test('basic example', function () {
    $this->browse(function (Browser $browser) {
        $browser->visit('/')              // Navigue vers la route racine (welcome)
            ->assertSee('Laravel');       // Vérifie que le texte « Laravel » est visible dans le DOM
    });
});
