<?php

use App\Models\Post; // Modèle article — factory avec relation user
use App\Models\User; // Modèle utilisateur — factory avec rôle user/admin

// Test PostPolicy : l'auteur peut modifier son propre article
it('allows user to update own post', function () {
    $user = User::factory()->create();              // Utilisateur standard (role = user)
    $post = Post::factory()->for($user)->create();  // Article lié à cet utilisateur (user_id)
    expect($user->can('update', $post))->toBeTrue(); // Gate/Policy : update autorisé pour l'auteur
});

// Test PostPolicy : un autre utilisateur ne peut pas modifier l'article d'autrui
it('forbids user from updating others post', function () {
    $author = User::factory()->create();             // Auteur de l'article
    $other  = User::factory()->create();             // Utilisateur tiers
    $post   = Post::factory()->for($author)->create();
    expect($other->can('update', $post))->toBeFalse(); // Policy refuse update si pas auteur ni admin
});

// Test PostPolicy : un admin peut modifier n'importe quel article
it('allows admin to update any post', function () {
    $admin  = User::factory()->create(['role' => 'admin']); // Rôle admin (Exo 3)
    $post   = Post::factory()->create();                     // Article d'un autre auteur
    expect($admin->can('update', $post))->toBeTrue();
});

// Test PostPolicy : un admin peut supprimer n'importe quel article
it('allows admin to delete any post', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $post  = Post::factory()->create();
    expect($admin->can('delete', $post))->toBeTrue();
});

// Test PostPolicy : un utilisateur ne peut pas supprimer l'article d'un autre
it('forbids user from deleting others post', function () {
    $author = User::factory()->create();
    $other  = User::factory()->create();
    $post   = Post::factory()->for($author)->create();
    expect($other->can('delete', $post))->toBeFalse();
});
