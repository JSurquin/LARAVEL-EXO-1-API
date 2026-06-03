{{-- Layout app — navbar + contenu principal (Exo 3 auth, Exo 4 thème session) --}}
<head>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
@props(['header' => null])
<nav class="bg-white border-b px-4 py-3 flex flex-wrap gap-4 items-center">
    @auth
        <a href="{{ route('newsletters.index') }}">Newsletters</a>
        <a href="{{ route('dashboard') }}">Dashboard</a>
        <a href="{{ route('posts.index') }}">Articles</a>
        <a href="{{ route('stats.index') }}">Statistiques</a>
        <a href="{{ route('preferences.index') }}">Préférences</a>
        <span class="text-sm text-gray-500">{{ auth()->user()->name }}</span>
        <form method="POST" action="{{ route('logout') }}" class="ml-auto">@csrf
            <button type="submit" class="text-sm text-red-600">Déconnexion</button>
        </form>
    @else
        <a href="{{ route('login') }}">Connexion</a>
        <a href="{{ route('register') }}">Inscription</a>
    @endauth
</nav>
{{-- Exo 4 : applique le thème sombre si session('theme') === 'dark' (stocké en Redis) --}}
<main class="max-w-5xl mx-auto p-6 {{ session('theme', 'light') === 'dark' ? 'bg-gray-900 text-white' : '' }}">
    @isset($header)<h1 class="text-xl font-semibold mb-4">{{ $header }}</h1>@endisset
    {{ $slot }}
</main>
