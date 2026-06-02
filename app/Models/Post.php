<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

// Modèle Eloquent pour les articles (posts)
class Post extends Model
{
    // Champs autorisés en assignation de masse
    protected $fillable = ['title', 'body', 'user_id'];

    // Relation : un article appartient à un utilisateur (auteur)
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
