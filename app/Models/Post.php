<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;

// Modèle Eloquent pour les articles (posts)
class Post extends Model
{
    use HasFactory;

    // Champs autorisés en assignation de masse
    protected $fillable = ['title', 'body', 'user_id'];

    // Relation : un article appartient à un utilisateur (auteur)
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
