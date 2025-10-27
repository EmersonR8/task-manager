<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Project;
use App\Models\Task;
use Illuminate\Support\Facades\Hash;

class TestDataSeeder extends Seeder
{
    public function run()
    {
        // 🔹 Crear usuarios
        $admin = User::create([
            'name' => 'Admin Uno',
            'email' => 'admin@example.com',
            'password' => Hash::make('123456'),
            'role' => 'admin',
        ]);

        $dev1 = User::create([
            'name' => 'Emerson Rosales',
            'email' => 'emerson@example.com',
            'password' => Hash::make('123456'),
            'role' => 'developer',
        ]);

        $dev2 = User::create([
            'name' => 'Dev Dos',
            'email' => 'dev2@example.com',
            'password' => Hash::make('123456'),
            'role' => 'developer',
        ]);

        // 🔹 Crear proyectos
        $project1 = Project::create([
            'user_id' => $dev1->id,
            'name' => 'Proyecto Emerson',
            'description' => 'Proyecto asignado a Emerson',
            'progress' => 0,
        ]);

        $project2 = Project::create([
            'user_id' => $dev2->id,
            'name' => 'Proyecto Dev Dos',
            'description' => 'Proyecto asignado a Dev Dos',
            'progress' => 0,
        ]);

        $project3 = Project::create([
            'user_id' => $dev1->id,
            'name' => 'Segundo Proyecto Emerson',
            'description' => 'Otro proyecto para Emerson',
            'progress' => 0,
        ]);

        // 🔹 Crear tareas
        Task::create([
            'project_id' => $project1->id,
            'assigned_to' => $dev1->id,
            'title' => 'Tarea 1',
            'description' => 'Primera tarea del proyecto',
            'status' => 'pendiente',
        ]);

        Task::create([
            'project_id' => $project1->id,
            'assigned_to' => $dev1->id,
            'title' => 'Tarea 2',
            'description' => 'Segunda tarea del proyecto',
            'status' => 'en_progreso',
        ]);

        Task::create([
            'project_id' => $project2->id,
            'assigned_to' => $dev2->id,
            'title' => 'Tarea A',
            'description' => 'Tarea completada del proyecto Dev Dos',
            'status' => 'completada',
        ]);

        Task::create([
            'project_id' => $project3->id,
            'assigned_to' => $dev1->id,
            'title' => 'Tarea X',
            'description' => 'Tarea pendiente del segundo proyecto de Emerson',
            'status' => 'pendiente',
        ]);
    }
}
