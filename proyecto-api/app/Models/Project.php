<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'description',
        'progress',
    ];

    // Un proyecto pertenece a un usuario
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Un proyecto tiene muchas tareas
    public function tasks()
    {
        return $this->hasMany(Task::class);
    }
}
