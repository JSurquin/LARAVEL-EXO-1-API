<?php

use App\Models\Post;
use App\Models\User;

it('allows user to update own post', function () {
    $user = User::factory()->create();
    $post = Post::factory()->for($user)->create();
    expect($user->can('update', $post))->toBeTrue();
});

it('forbids user from updating others post', function () {
    $author = User::factory()->create();
    $other  = User::factory()->create();
    $post   = Post::factory()->for($author)->create();
    expect($other->can('update', $post))->toBeFalse();
});

it('allows admin to update any post', function () {
    $admin  = User::factory()->create(['role' => 'admin']);
    $post   = Post::factory()->create();
    expect($admin->can('update', $post))->toBeTrue();
});

it('allows admin to delete any post', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $post  = Post::factory()->create();
    expect($admin->can('delete', $post))->toBeTrue();
});

it('forbids user from deleting others post', function () {
    $author = User::factory()->create();
    $other  = User::factory()->create();
    $post   = Post::factory()->for($author)->create();
    expect($other->can('delete', $post))->toBeFalse();
});