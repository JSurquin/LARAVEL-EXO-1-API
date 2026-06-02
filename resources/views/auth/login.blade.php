<x-guest-layout>
    <form method="POST" action="{{ route('login') }}" class="space-y-4">
        @csrf
        <input type="email" name="email" required class="w-full border rounded p-2" placeholder="Email">
        <input type="password" name="password" required class="w-full border rounded p-2" placeholder="Mot de passe">
        @error('email')<p class="text-red-600 text-sm">{{ $message }}</p>@enderror
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded w-full">Se connecter</button>
    </form>
</x-guest-layout>