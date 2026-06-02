{{-- Affichage d'un article — GET /posts/{post} --}}
<x-app-layout>
    <h2 class="text-xl font-semibold">{{ $post->title }}</h2>
    <p class="text-sm text-gray-500">Par {{ $post->user->name }}</p>
    <div class="mt-4 whitespace-pre-wrap">{{ $post->body }}</div>
    <a href="{{ route('posts.index') }}" class="inline-block mt-6 text-blue-600">← Retour</a>
</x-app-layout>
