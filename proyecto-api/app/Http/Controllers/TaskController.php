<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TaskController extends Controller
{
    // Listar tareas, con filtros opcionales
    public function index(Request $request)
    {
        $query = Task::query();

        if ($request->has('project_id')) {
            $query->where('project_id', $request->project_id);
        }

        if ($request->has('assigned_to')) {
            $query->where('assigned_to', $request->assigned_to);
        }

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        $tasks = $query->with('assignedUser', 'project')->get();

        return response()->json($tasks);
    }

    // Crear tarea
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'project_id' => 'required|exists:projects,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'assigned_to' => 'nullable|exists:users,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $task = Task::create([
            'project_id' => $request->project_id,
            'title' => $request->title,
            'description' => $request->description,
            'assigned_to' => $request->assigned_to,
        ]);

        $this->updateProjectProgress($request->project_id);

        return response()->json($task, 201);
    }

    // Mostrar tarea específica
    public function show(Task $task)
    {
        $task->load('assignedUser', 'project');
        return response()->json($task);
    }

    // Actualizar tarea
    public function update(Request $request, Task $task)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'nullable|in:pendiente,en_progreso,completada',
            'assigned_to' => 'nullable|exists:users,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $task->update($request->only(['title', 'description', 'status', 'assigned_to']));

        $this->updateProjectProgress($task->project_id);

        return response()->json($task);
    }

    // Eliminar tarea
    public function destroy(Task $task)
    {
        $projectId = $task->project_id;
        $task->delete();

        $this->updateProjectProgress($projectId);

        return response()->json(['message' => 'Tarea eliminada']);
    }

    // Actualiza el progreso del proyecto
    private function updateProjectProgress($projectId)
    {
        $project = Project::find($projectId);
        if (!$project) return;

        $total = $project->tasks()->count();
        if ($total === 0) {
            $project->progress = 0;
        } else {
            $completed = $project->tasks()->where('status', 'completada')->count();
            $project->progress = ($completed / $total) * 100;
        }

        $project->save();
    }
}
