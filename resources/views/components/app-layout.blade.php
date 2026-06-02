{{-- Layout app mis à jour (Exo 3) — navbar avec liens auth et navigation --}}
<head>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
@props(['header' => null])
<nav class="bg-white border-b px-4 py-3 flex flex-wrap gap-4 items-center">
@if(auth()->user()?->isAdmin())
    <a href="{{ route('newsletters.index') }}">Newsletters</a>
@endif

    @auth
        {{-- Liens visibles uniquement si connecté --}}
        <a href="{{ route('dashboard') }}">Dashboard</a>
        <a href="{{ route('posts.index') }}">Articles</a>
        <span class="text-sm text-gray-500">{{ auth()->user()->name }}</span>
        {{-- Déconnexion Fortify (session web) --}}
        <form method="POST" action="{{ route('logout') }}" class="ml-auto">@csrf
            <button type="submit" class="text-sm text-red-600">Déconnexion</button>
        </form>
    @else
        {{-- Liens visibles si non connecté --}}
        <a href="{{ route('login') }}">Connexion</a>
        <a href="{{ route('register') }}">Inscription</a>
    @endauth
</nav>
<main class="max-w-5xl mx-auto p-6 {{ session('theme', 'light') === 'dark' ? 'bg-gray-900 text-white' : '' }}">
    @isset($header)<h1 class="text-xl font-semibold mb-4">{{ $header }}</h1>@endisset
    {{ $slot }}
</main>
