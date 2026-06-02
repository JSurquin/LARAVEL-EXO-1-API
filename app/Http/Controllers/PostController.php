<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

// CRUD web des articles — protégé par middleware auth + PostPolicy
class PostController extends Controller
{
    // Trait Laravel pour appeler $this->authorize() dans le contrôleur
    use AuthorizesRequests;

    // GET /posts — liste tous les articles avec leur auteur
    public function index()
    {
        $this->authorize('viewAny', Post::class);
        return view('posts.index', [
            'posts' => Post::with('user')->latest()->get(),
        ]);
    }

    // GET /posts/create — formulaire de création
    public function create()
    {
        $this->authorize('create', Post::class);
        return view('posts.create');
    }

    // GET /posts/{post} — affiche un article
    public function show(Post $post)
    {
        return view('posts.show', compact('post'));
    }

    // POST /posts — enregistre un nouvel article lié à l'utilisateur connecté
    public function store(Request $request)
    {
        $this->authorize('create', Post::class);
        $validated = $request->validate([
            'title' => 'required|max:255',
            'body'  => 'required',
        ]);
        auth()->user()->posts()->create($validated);
        return redirect()->route('posts.index')->with('success', 'Article créé !');
    }

    // GET /posts/{post}/edit — formulaire d'édition
    public function edit(Post $post)
    {
        $this->authorize('update', $post);
        return view('posts.edit', compact('post'));
    }

    // PUT /posts/{post} — met à jour un article existant
    public function update(Request $request, Post $post)
    {
        $this->authorize('update', $post);
        $validated = $request->validate(['title' => 'required|max:255', 'body' => 'required']);
        $post->update($validated);
        return redirect()->route('posts.index')->with('success', 'Article modifié !');
    }

    // DELETE /posts/{post} — supprime un article
    public function destroy(Post $post)
    {
        $this->authorize('delete', $post);
        $post->delete();
        return redirect()->route('posts.index')->with('success', 'Article supprimé !');
    }
}
