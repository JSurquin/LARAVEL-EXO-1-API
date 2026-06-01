<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;

class TaskController extends Controller
{
    /**
     * GET /api/tasks — Liste paginée (10/page), filtrable par ?status=
     */
    public function index()
    {
        return Task::query()
        ->when(request('status'), fn($q, $s) => $q->where('status', $s))
        ->paginate(10);
    }

    /**
     * POST /api/tasks — Crée une tâche (validation via StoreTaskRequest)
     */
    public function store(StoreTaskRequest $request)
    {
        return response()->json(Task::create($request->validated()), 201);
    }

    /**
     * GET /api/tasks/{task} — Affiche une tâche (route model binding)
     */
    public function show(Task $task)
    {
        return $task;
    }

    /**
     * PUT/PATCH /api/tasks/{task} — Met à jour une tâche
     */
    public function update(UpdateTaskRequest $request, Task $task)
    {
        $task->update($request->validated());
        return $task;
    }

    /**
     * DELETE /api/tasks/{task} — Supprime une tâche (204 No Content)
     */
    public function destroy(Task $task)
    {
        $task->delete();
        return response()->noContent();
    }
}
