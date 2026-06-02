<?php

namespace App\Policies;

use App\Models\Post;
use App\Models\User;

// Policy d'autorisation — règles d'accès CRUD sur les articles
class PostPolicy
{
    // Tout utilisateur authentifié peut voir la liste
    public function viewAny(User $user): bool
    {
        return true;
    }

    // Tout utilisateur authentifié peut lire un article
    public function view(User $user, Post $post): bool
    {
        return true;
    }

    // Tout utilisateur authentifié peut créer un article
    public function create(User $user): bool
    {
        return true;
    }

    // Modification : auteur OU admin
    public function update(User $user, Post $post): bool
    {
        return $user->id === $post->user_id || $user->isAdmin();
    }

    // Suppression : auteur OU admin
    public function delete(User $user, Post $post): bool
    {
        return $user->id === $post->user_id || $user->isAdmin();
    }

    public function restore(User $user, Post $post): bool
    {
        return false;
    }

    public function forceDelete(User $user, Post $post): bool
    {
        return false;
    }
}
