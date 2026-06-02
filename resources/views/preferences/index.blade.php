<x-app-layout>
    <x-slot name="header"><h2>Mes préférences</h2></x-slot>
    <div class="max-w-xl mx-auto py-8">
        @if(session('success'))
            <div class="bg-green-100 text-green-800 p-3 rounded mb-4">{{ session('success') }}</div>
        @endif
        <form method="POST" action="{{ route('preferences.store') }}" class="space-y-4">
            @csrf
            <div>
                <label class="font-medium">Thème</label>
                <select name="theme" class="border rounded p-2 w-full mt-1">
                    <option value="light" {{ $theme === 'light' ? 'selected' : '' }}>Clair</option>
                    <option value="dark"  {{ $theme === 'dark'  ? 'selected' : '' }}>Sombre</option>
                </select>
            </div>
            <div>
                <label class="font-medium">Langue</label>
                <select name="locale" class="border rounded p-2 w-full mt-1">
                    <option value="fr" {{ $locale === 'fr' ? 'selected' : '' }}>Français</option>
                    <option value="en" {{ $locale === 'en' ? 'selected' : '' }}>English</option>
                </select>
            </div>
            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded">Enregistrer</button>
        </form>
        <p class="mt-6 text-sm text-gray-500">
            Thème actuel : <strong>{{ $theme }}</strong> — Langue : <strong>{{ $locale }}</strong>
        </p>
    </div>
</x-app-layout>

