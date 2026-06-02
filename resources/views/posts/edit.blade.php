{{-- Formulaire d'édition d'article — PUT /posts/{post} --}}
<x-app-layout>
    <form method="POST" action="{{ route('posts.update', $post) }}">
        @csrf @method('PUT')
        <input type="text" name="title" value="{{ old('title', $post->title) }}" required
               class="w-full border rounded p-2 mb-4">
        <textarea name="body" rows="8" required
                  class="w-full border rounded p-2 mb-4">{{ old('body', $post->body) }}</textarea>
        <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded">Mettre à jour</button>
    </form>
</x-app-layout>
