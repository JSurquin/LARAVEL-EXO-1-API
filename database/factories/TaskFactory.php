<?php

namespace Database\Factories;

use App\Models\Task;                              // Modèle cible — utilisé par TaskApiTest
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Factory Task — génère des tâches factices pour les tests API Pest (TaskApiTest).
 *
 * @extends Factory<Task>
 */
class TaskFactory extends Factory
{
    /**
     * État par défaut d'une tâche créée via Task::factory()->create().
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title'       => fake()->sentence(4),                              // Titre court (4 mots)
            'description' => fake()->optional()->paragraph(),                  // Description optionnelle (parfois null)
            'status'      => fake()->randomElement(['todo', 'in_progress', 'done']), // Statut aléatoire parmi les 3 valeurs
            'due_date'    => fake()->optional()->dateTimeBetween('now', '+3 months'), // Date d'échéance optionnelle
        ];
    }
}
