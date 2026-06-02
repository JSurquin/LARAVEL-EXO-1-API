<x-app-layout>
    <x-slot name="header"><h2>Newsletters</h2></x-slot>
    <div class="max-w-3xl mx-auto py-8">
        @if(session('success'))<p class="text-green-600 mb-4">{{ session('success') }}</p>@endif
        <a href="{{ route('newsletters.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded">Nouvelle newsletter</a>
        <ul class="mt-6 space-y-3">
            @foreach($newsletters as $n)
                <li>
                    <strong>{{ $n->subject }}</strong>
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