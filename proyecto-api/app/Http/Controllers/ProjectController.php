<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ProjectController extends Controller
{
    // Listar proyectos
    public function index()
    {
        $user = Auth::user();

        // Si es admin ve todos, si es dev ve solo sus proyectos
        $projects = $user->role === 'admin' ? Project::with('user', 'tasks')->get() : $user->projects()->with('tasks')->get();

        return response()->json($projects);
    }

    // Crear proyecto
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $project = Project::create([
            'user_id' => $request->user_id ?? auth()->id(),
            'name' => $request->name,
            'description' => $request->description,
            'progress' => 0,
        ]);

        return response()->json($project, 201);
    }

    // Mostrar proyecto específico
    public function show(Project $project)
    {
         $project->load(['tasks.assignedUser']); // <--- Aquí se carga el usuario asignado
    return response()->json($project);
    }

    // Actualizar proyecto
   /* public function update(Request $request, Project $project)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $project->update($request->only(['name', 'description']));
        return response()->json($project);
    }*/

    // Actualizar proyecto
    public function update(Request $request, Project $project)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'user_id' => 'nullable|integer|exists:users,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Actualizamos solo los campos permitidos
        $data = $request->only(['name', 'description', 'user_id']);
        $project->update($data);

        return response()->json($project);
    }
    

    // Eliminar proyecto
    public function destroy(Project $project)
    {
        $project->delete();
        return response()->json(['message' => 'Proyecto eliminado']);
    }
}
