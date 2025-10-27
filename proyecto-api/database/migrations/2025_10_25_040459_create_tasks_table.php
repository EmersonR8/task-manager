<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->onDelete('cascade'); // si proyecto se borra, tarea se borra
            $table->foreignId('assigned_to')->nullable()->constrained('users')->onDelete('set null'); // si usuario se borra, asignación a null
            $table->string('title');
            $table->text('description')->nullable();
            $table->enum('status', ['pendiente', 'en_progreso', 'completada'])->default('pendiente');
            $table->timestamps();
            $table->engine = 'InnoDB';
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
