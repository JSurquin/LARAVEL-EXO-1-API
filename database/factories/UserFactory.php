<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * Factory User — génère des utilisateurs pour les tests Pest et Dusk.
 *
 * @extends Factory<User>
 */
class UserFactory extends Factory
{
    /**
     * Mot de passe en cache (hash) — non utilisé ici car password = 'password' en clair pour les tests.
     */
    protected static ?string $password;

    /**
     * État par défaut d'un utilisateur créé via User::factory()->create().
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),                    // Nom complet aléatoire
            'email' => fake()->unique()->safeEmail(),    // E-mail unique (évite les collisions en tests)
            'email_verified_at' => now(),                // Compte vérifié par défaut
            'password' => 'password',                    // Mot de passe en clair — Fortify/Auth le hashe au login (Dusk, tests)
            'remember_token' => Str::random(10),       // Token « se souvenir de moi »
            'role' => 'user',                            // Rôle par défaut — passer ['role' => 'admin'] pour les tests admin
        ];
    }

    /**
     * State : utilisateur avec e-mail non vérifié.
     * Usage : User::factory()->unverified()->create()
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null, // Supprime la date de vérification
        ]);
    }
}
