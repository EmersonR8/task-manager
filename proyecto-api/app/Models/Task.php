<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'assigned_to',
        'title',
        'description',
        'status',
    ];

    // Una tarea pertenece a un proyecto
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    // Una tarea puede estar asignada a un usuario
    public function assignedUser()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }
}
