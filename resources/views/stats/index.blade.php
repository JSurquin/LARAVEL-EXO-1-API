<x-app-layout title="Statistiques">
    <h1 class="text-2xl font-bold mb-6">Statistiques de l'application</h1>

    {{-- Flash success (préférences, etc.) — le vidage cache redirige vers le dashboard --}}
    @if(session('success'))
        <div class="bg-green-100 border border-green-300 text-green-800 px-4 py-3 rounded mb-6">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-2 gap-4">
        <div class="bg-white rounded-xl shadow p-6 text-center">
            <p class="text-4xl font-bold text-indigo-600">{{ $stats['users'] }}</p>
            <p class="text-gray-500 mt-2">Utilisateurs</p>
        </div>
        <div class="bg-white rounded-xl shadow p-6 text-center">
            <p class="text-4xl font-bold text-indigo-600">{{ $stats['tasks'] }}</p>
            <p class="text-gray-500 mt-2">Tâches</p>
        </div>
    </div>

    @auth
        @if(auth()->user()->isAdmin())
            <form method="POST" action="{{ route('cache.flush') }}" class="mt-6">
                @csrf
                <button class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">
                    🗑 Vider le cache
                </button>
            </form>
            <p class="text-xs text-gray-400 mt-2">
                Après vidage, la prochaine visite recompte en base de données.
            </p>
        @endif
    @endauth
</x-app-layout>