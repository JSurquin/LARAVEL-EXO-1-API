<x-app-layout>
    <form method="POST" action="{{ route('posts.store') }}">
        @csrf
        <input type="text" name="title" placeholder="Titre" required
               class="w-full border rounded p-2 mb-4" value="{{ old('title') }}">
        <textarea name="body" rows="8" placeholder="Contenu" required
                  class="w-full border rounded p-2 mb-4">{{ old('body') }}</textarea>
        @error('title')<p class="text-red-500 text-sm">{{ $message }}</p>@enderror
        <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded">Publier</button>
    </form>
</x-app-layout>