<?php

// Exo 6 — configuration globale Pest : lie les tests Browser à DuskTestCase
pest()->extend(Tests\DuskTestCase::class)
//  ->use(Illuminate\Foundation\Testing\DatabaseMigrations::class) // Optionnel : migrations avant chaque test Dusk
    ->in('Browser'); // Applique DuskTestCase à tous les fichiers du dossier tests/Browser/

use Illuminate\Foundation\Testing\RefreshDatabase; // Réinitialise la BDD SQLite en mémoire entre chaque test Feature
use Tests\TestCase;                                 // Classe de base Laravel (HTTP, facades, helpers $this->getJson, etc.)

/*
|--------------------------------------------------------------------------
| Test Case — tests Feature (Pest)
|--------------------------------------------------------------------------
|
| Les tests dans tests/Feature/ héritent de TestCase + RefreshDatabase :
| chaque test repart d'une base vide (migrations rejouées automatiquement).
|
*/

pest()->extend(TestCase::class)
    ->use(RefreshDatabase::class) // Vide et recrée les tables avant chaque test Feature
    ->in('Feature');              // Applique cette config à tests/Feature/*.php

/*
|--------------------------------------------------------------------------
| Expectations personnalisées (optionnel)
|--------------------------------------------------------------------------
|
| Permet d'étendre l'API expect() de Pest avec des assertions réutilisables.
|
*/

expect()->extend('toBeOne', function () {
    return $this->toBe(1); // Exemple : expect($x)->toBeOne() équivaut à expect($x)->toBe(1)
});

/*
|--------------------------------------------------------------------------
| Helpers globaux (optionnel)
|--------------------------------------------------------------------------
|
| Fonctions globales partagées entre tous les fichiers de test.
|
*/

function something()
{
    // ..
}
