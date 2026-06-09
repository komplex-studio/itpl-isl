<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Indian Sports League') · ISL 2026</title>
    <meta name="description" content="The Indian Sports League — national multi-sport competition. Athlete registration, live schedules, brackets, results and digital certification.">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-ink-50">
    <x-site-nav />

    <main>
        @yield('content')
    </main>

    <x-site-footer />
</body>
</html>
