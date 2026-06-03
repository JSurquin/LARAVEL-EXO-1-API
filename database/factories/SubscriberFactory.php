<?php

namespace Database\Factories;

use App\Models\Subscriber;                        // Modèle abonné — utilisé par NewsletterJobTest
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Factory Subscriber — génère des abonnés factices pour les tests du job newsletter.
 *
 * @extends Factory<Subscriber>
 */
class SubscriberFactory extends Factory
{
    /**
     * État par défaut d'un abonné créé via Subscriber::factory()->create().
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'email' => fake()->unique()->safeEmail(), // E-mail unique (contrainte unique en BDD)
            'name' => fake()->name(),                  // Nom de l'abonné
        ];
    }
}
