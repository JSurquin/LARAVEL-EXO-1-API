<x-app-layout header="Tableau de bord">
    <p class="mb-4">Bienvenue, {{ auth()->user()->name }} ({{ auth()->user()->role }})</p>
    <a href="{{ route('posts.index') }}" class="text-blue-600 underline">→ Gérer les articles</a>
</x-app-layout>