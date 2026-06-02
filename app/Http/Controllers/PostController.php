<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class PostController extends Controller
{

    use AuthorizesRequests;


    public function index()
    {
        $this->authorize('viewAny', Post::class);
        return view('posts.index', [
            'posts' => Post::with('user')->latest()->get(),
        ]);
    }

    public function create()
    {
        $this->authorize('create', Post::class);
        return view('posts.create');
    }

    public function show(Post $post)
    {
        return view('posts.show', compact('post'));
    }

    /**
     * Store a newly created resource in storage.
     */
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

    public function edit(Post $post)
    {
        $this->authorize('update', $post);
        return view('posts.edit', compact('post'));
    }

    public function update(Request $request, Post $post)
    {
        $this->authorize('update', $post);
        $validated = $request->validate(['title' => 'required|max:255', 'body' => 'required']);
        $post->update($validated);
        return redirect()->route('posts.index')->with('success', 'Article modifié !');
    }

    public function destroy(Post $post)
    {
        $this->authorize('delete', $post);
        $post->delete();
        return redirect()->route('posts.index')->with('success', 'Article supprimé !');
    }

}
