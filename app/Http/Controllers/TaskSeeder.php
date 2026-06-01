<?php

use App\Models\Task;

class TaskSeeder extends Seeder
{
    public function run(): void
    {
        Task::create([
        'title'       => 'Configurer la base SQLite',
        'description' => 'Créer database.sqlite et .env',
        'status'      => 'done',
    ]);

    Task::create([
        'title'    => 'Implémenter le CRUD API',
        'status'   => 'in_progress',
        'due_date' => now()->addDays(7),
    ]);

    Task::create([
        'title'    => 'Tester avec Postman',
        'status'   => 'todo',
        'due_date' => now()->addDays(3),
    ]);
    }
}