<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Task extends Model
{
    use HasFactory;

    // Champs autorisés en assignation de masse (create / update)
    protected $fillable = ['title', 'description', 'status', 'due_date'];

    // Cast automatique de due_date en objet Carbon
    protected $casts = [
        'due_date' => 'date',
    ];
}
