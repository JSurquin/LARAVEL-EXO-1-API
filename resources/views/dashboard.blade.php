{{-- Dashboard — page d'accueil après connexion (route /dashboard) --}}
<x-app-layout header="Tableau de bord">
    <p class="mb-4">Bienvenue, {{ auth()->user()->name }} ({{ auth()->user()->role }})</p>
    <a href="{{ route('posts.index') }}" class="text-blue-600 underline">→ Gérer les articles</a>
    @if(session('success'))
    <div class="bg-green-100 border border-green-300 text-green-800 px-4 py-3 rounded mb-6">
        {{ session('success') }}
    </div>
    @endif
</x-app-layout>
