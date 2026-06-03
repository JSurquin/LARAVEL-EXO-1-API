{{-- Vue liste des newsletters — utilise le layout principal avec navbar --}}
<x-app-layout>
    {{-- En-tête de la page injecté dans le slot "header" du layout --}}
    <x-slot name="header"><h2>Newsletters</h2></x-slot>

    <div class="max-w-3xl mx-auto py-8">
        {{-- Affiche le message flash de succès après création d'une newsletter --}}
        @if(session('success'))<p class="text-green-600 mb-4">{{ session('success') }}</p>@endif

        {{-- Lien vers le formulaire de création d'une nouvelle newsletter --}}
        <a href="{{ route('newsletters.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded">Nouvelle newsletter</a>

        {{-- Liste des newsletters récupérées par le contrôleur --}}
        <ul class="mt-6 space-y-3">
            @foreach($newsletters as $n)
                <li>
                    {{-- Sujet de la newsletter --}}
                    <strong>{{ $n->subject }}</strong>

                    {{-- Indicateur visuel : envoyée (sent_at renseigné) ou en cours (worker en attente) --}}
                    @if($n->sent_at)
                        <span class="text-green-600"> — ✓ Envoyée le {{ $n->sent_at->format('d/m/Y H:i') }}</span>
                    @else
                        <span class="text-amber-600"> — ⏳ En cours d'envoi (rechargez après le worker)</span>
                    @endif
                </li>
            @endforeach
        </ul>
    </div>
</x-app-layout>
