<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens; // Permet createToken() pour l'auth API Sanctum
use App\Models\Post;

#[Fillable(['name', 'email', 'password', 'role'])] // Champs assignables (inclut role ajouté en Exo 3)
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, HasApiTokens; // HasApiTokens = tokens Bearer Sanctum

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed', // Hash automatique du mot de passe à l'enregistrement
        ];
    }

    // Relation : un utilisateur possède plusieurs articles
    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    // Helper : vérifie si l'utilisateur a le rôle admin
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }
}
