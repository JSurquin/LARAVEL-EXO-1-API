{{-- Vue formulaire de création d'une newsletter --}}
<x-app-layout>
    {{-- En-tête de page injecté dans le slot "header" du layout --}}
    <x-slot name="header"><h2>Créer une newsletter</h2></x-slot>

    <div class="max-w-2xl mx-auto py-8">
        {{-- Formulaire POST vers newsletters.store — traité par NewsletterController@store --}}
        <form method="POST" action="{{ route('newsletters.store') }}">
            {{-- Token CSRF obligatoire pour protéger le formulaire contre les attaques cross-site --}}
            @csrf

            {{-- Champ sujet : correspond à la colonne subject de la table newsletters --}}
            <input type="text" name="subject" placeholder="Sujet" required class="w-full border rounded p-2 mb-4">

            {{-- Champ corps : texte libre, correspond à la colonne body --}}
            <textarea name="body" rows="10" placeholder="Corps" required class="w-full border rounded p-2 mb-4"></textarea>

            {{-- Bouton de soumission : déclenche la validation + dispatch du job --}}
            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded">Envoyer</button>
        </form>
    </div>
</x-app-layout>
