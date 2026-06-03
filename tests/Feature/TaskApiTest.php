<?php

use App\Models\Task;

it('lists tasks', function () {
    Task::factory()->count(5)->create();
    $this->getJson('/api/tasks')->assertOk()->assertJsonCount(5, 'data');
});

it('creates a task', function () {
    $this->postJson('/api/tasks', ['title' => 'Ma tâche', 'status' => 'todo'])
        ->assertCreated()->assertJsonFragment(['title' => 'Ma tâche']);
    $this->assertDatabaseHas('tasks', ['title' => 'Ma tâche']);
});

it('validates title on store', function () {
    $this->postJson('/api/tasks', [])->assertUnprocessable();
});

it('shows a task', function () {
    $task = Task::factory()->create();
    $this->getJson("/api/tasks/{$task->id}")
        ->assertOk()->assertJsonFragment(['id' => $task->id]);
});

it('returns 404 for missing task', function () {
    $this->getJson('/api/tasks/9999')->assertNotFound();
});

it('updates a task', function () {
    $task = Task::factory()->create(['status' => 'todo']);
    $this->putJson("/api/tasks/{$task->id}", ['status' => 'done'])
        ->assertOk()->assertJsonFragment(['status' => 'done']);
});

it('deletes a task', function () {
    $task = Task::factory()->create();
    $this->deleteJson("/api/tasks/{$task->id}")->assertNoContent();
});

it('filters by status', function () {
    Task::factory()->count(3)->create(['status' => 'todo']);
    Task::factory()->count(2)->create(['status' => 'done']);
    $this->getJson('/api/tasks?status=todo')->assertOk()->assertJsonCount(3, 'data');
});