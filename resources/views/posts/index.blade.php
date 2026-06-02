<x-app-layout>
    <a href="{{ route('posts.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded">Nouvel article</a>
    @foreach($posts as $post)
        <div class="bg-white rounded-lg shadow p-4 mt-4">
            <h3 class="font-bold">{{ $post->title }}</h3>
            <p class="text-sm text-gray-500">Par {{ $post->user->name }}</p>
            <p class="mt-2">{{ Str::limit($post->body, 120) }}</p>
            @can('update', $post)
                <a href="{{ route('posts.edit', $post) }}" class="text-sm bg-yellow-500 text-white px-3 py-1 rounded">Modifier</a>
            @endcan
            @can('delete', $post)
                <form method="POST" action="{{ route('posts.destroy', $post) }}" class="inline">
                    @csrf @method('DELETE')
                    <button class="text-sm bg-red-600 text-white px-3 py-1 rounded">Supprimer</button>
                </form>
            @endcan
        </div>
    @endforeach
</x-app-layout>