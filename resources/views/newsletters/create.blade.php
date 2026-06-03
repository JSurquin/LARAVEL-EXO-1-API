<x-app-layout>
    <x-slot name="header"><h2>Créer une newsletter</h2></x-slot>
    <div class="max-w-2xl mx-auto py-8">
        <form method="POST" action="{{ route('newsletters.store') }}">
            @csrf
            <input type="text" name="subject" placeholder="Sujet" required class="w-full border rounded p-2 mb-4">
            <textarea name="body" rows="10" placeholder="Corps" required class="w-full border rounded p-2 mb-4"></textarea>
            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded">Envoyer</button>
        </form>
    </div>
</x-app-layout>