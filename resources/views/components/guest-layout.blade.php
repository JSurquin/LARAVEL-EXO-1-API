<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>{{ $title ?? config('app.name') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-gray-100 flex items-center justify-center">
    <div class="w-full max-w-md bg-white rounded-xl shadow-md p-8">{{ $slot }}</div>
</body>
</html>