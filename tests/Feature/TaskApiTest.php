<?php

use App\Models\Task; // Modèle Task — factory pour générer des données de test

// Test : GET /api/tasks retourne une liste paginée de 5 tâches
it('lists tasks', function () {
    Task::factory()->count(5)->create(); // Insère 5 tâches en BDD (SQLite en mémoire via RefreshDatabase)
    $this->getJson('/api/tasks')         // Requête HTTP GET JSON (route publique ajoutée pour les tests Exo 6)
        ->assertOk()                     // Statut HTTP 200
        ->assertJsonCount(5, 'data');   // Vérifie que la clé "data" contient exactement 5 éléments (pagination Laravel)
});

// Test : POST /api/tasks crée une tâche et la persiste en base
it('creates a task', function () {
    $this->postJson('/api/tasks', ['title' => 'Ma tâche', 'status' => 'todo']) // Corps JSON validé par StoreTaskRequest
        ->assertCreated()                          // Statut HTTP 201
        ->assertJsonFragment(['title' => 'Ma tâche']); // Vérifie que la réponse JSON contient le titre
    $this->assertDatabaseHas('tasks', ['title' => 'Ma tâche']); // Vérifie l'insertion en BDD
});

// Test : validation — title obligatoire, requête vide → 422 Unprocessable Entity
it('validates title on store', function () {
    $this->postJson('/api/tasks', [])->assertUnprocessable(); // Corps vide → erreurs de validation
});

// Test : GET /api/tasks/{id} retourne une tâche existante
it('shows a task', function () {
    $task = Task::factory()->create(); // Une tâche en base
    $this->getJson("/api/tasks/{$task->id}")
        ->assertOk()
        ->assertJsonFragment(['id' => $task->id]); // Vérifie que l'ID correspond
});

// Test : GET sur un ID inexistant → 404 Not Found
it('returns 404 for missing task', function () {
    $this->getJson('/api/tasks/9999')->assertNotFound();
});

// Test : PUT /api/tasks/{id} met à jour le statut
it('updates a task', function () {
    $task = Task::factory()->create(['status' => 'todo']);
    $this->putJson("/api/tasks/{$task->id}", ['status' => 'done'])
        ->assertOk()
        ->assertJsonFragment(['status' => 'done']); // Vérifie la mise à jour dans la réponse JSON
});

// Test : DELETE /api/tasks/{id} supprime la tâche (204 No Content)
it('deletes a task', function () {
    $task = Task::factory()->create();
    $this->deleteJson("/api/tasks/{$task->id}")->assertNoContent(); // 204 = suppression réussie sans corps
});

// Test : filtre ?status=todo ne retourne que les tâches au statut « todo »
it('filters by status', function () {
    Task::factory()->count(3)->create(['status' => 'todo']);
    Task::factory()->count(2)->create(['status' => 'done']);
    $this->getJson('/api/tasks?status=todo')
        ->assertOk()
        ->assertJsonCount(3, 'data'); // Seules les 3 tâches « todo » doivent apparaître
});
