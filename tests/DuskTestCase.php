<?php

namespace Tests;

use Facebook\WebDriver\Chrome\ChromeOptions;              // Options du navigateur Chrome (taille, headless, etc.)
use Facebook\WebDriver\Remote\DesiredCapabilities;        // Capacités du driver (navigateur, version)
use Facebook\WebDriver\Remote\RemoteWebDriver;            // Instance WebDriver qui pilote Chrome à distance
use Illuminate\Support\Collection;                        // Collection Laravel pour manipuler les arguments Chrome
use Laravel\Dusk\TestCase as BaseTestCase;                // Classe de base Dusk (browse(), visit(), assertSee(), etc.)
use PHPUnit\Framework\Attributes\BeforeClass;             // Attribut PHPUnit : exécute prepare() une fois par classe

// Classe abstraite parente de tous les tests Browser — configure ChromeDriver
abstract class DuskTestCase extends BaseTestCase
{
    /**
     * Lance ChromeDriver avant la première exécution de tests Browser de la classe.
     * Ignoré si l'app tourne dans Laravel Sail (Docker gère le driver).
     */
    #[BeforeClass]
    public static function prepare(): void
    {
        if (! static::runningInSail()) {
            static::startChromeDriver(['--port=9515']); // Démarre ChromeDriver sur le port 9515 (défaut Dusk)
        }
    }

    /**
     * Crée l'instance RemoteWebDriver qui contrôle le navigateur Chrome.
     * Appelée automatiquement par Dusk avant chaque test Browser.
     */
    protected function driver(): RemoteWebDriver
    {
        // Construit la liste des arguments Chrome (taille fenêtre, headless, etc.)
        $options = (new ChromeOptions)->addArguments(collect([
            $this->shouldStartMaximized() ? '--start-maximized' : '--window-size=1920,1080', // Plein écran ou 1920×1080
            '--disable-search-engine-choice-screen',  // Désactive l'écran de choix du moteur de recherche Chrome
            '--disable-smooth-scrolling',             // Désactive le défilement fluide (tests plus stables)
        ])->unless($this->hasHeadlessDisabled(), function (Collection $items) {
            // Mode headless : pas de fenêtre visible (CI, terminal sans GUI)
            return $items->merge([
                '--disable-gpu',
                '--headless=new',
            ]);
        })->all());

        // Connexion au ChromeDriver local (URL configurable via DUSK_DRIVER_URL dans .env.dusk.local)
        return RemoteWebDriver::create(
            $_ENV['DUSK_DRIVER_URL'] ?? env('DUSK_DRIVER_URL') ?? 'http://localhost:9515',
            DesiredCapabilities::chrome()->setCapability(
                ChromeOptions::CAPABILITY, $options // Passe les options Chrome au driver
            )
        );
    }
}
