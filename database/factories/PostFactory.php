<?php

namespace Database\Factories;

use App\Models\Post;                              // Modèle cible de cette factory
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;                              // Relation belongsTo — User::factory() crée l'auteur

/**
 * Factory Post — génère des articles factices pour les tests Pest (PostPolicyTest).
 *
 * @extends Factory<Post>
 */
class PostFactory extends Factory
{
    /**
     * État par défaut d'un article créé via Post::factory()->create().
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->sentence(6),           // Titre aléatoire (6 mots)
            'body' => fake()->paragraph(3, true),     // Corps : 3 paragraphes
            'user_id' => User::factory(),             // Crée automatiquement un User lié (relation for())
        ];
    }
}
