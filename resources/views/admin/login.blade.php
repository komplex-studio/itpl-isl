<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Login · ISL</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="grid min-h-screen place-items-center bg-ink-950 p-5">
    <div class="absolute -left-24 top-1/3 h-96 w-96 rounded-full bg-saffron-500/20 blur-3xl"></div>
    <div class="relative w-full max-w-sm">
        <div class="mb-8 text-center">
            <span class="inline-grid h-14 w-14 place-items-center rounded-2xl bg-gradient-to-br from-saffron-500 to-saffron-700 font-display text-xl font-900 text-white shadow-lg shadow-saffron-500/30">IS<span class="text-victory-400">L</span></span>
            <h1 class="mt-4 font-display text-2xl font-800 text-white">Organiser console</h1>
            <p class="mt-1 text-sm text-ink-400">Sign in to manage the Indian Sports League</p>
        </div>

        <form method="POST" action="{{ route('admin.login.attempt') }}" class="rounded-3xl bg-white p-7 shadow-2xl">
            @csrf
            @if ($errors->any())
                <div class="mb-5 rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700">
                    {{ $errors->first() }}
                </div>
            @endif
            <div class="mb-4">
                <label class="label" for="email">Email</label>
                <input id="email" type="email" name="email" value="{{ old('email', 'admin@isl.test') }}" class="input" required autofocus>
            </div>
            <div class="mb-4">
                <label class="label" for="password">Password</label>
                <input id="password" type="password" name="password" value="password" class="input" required>
            </div>
            <label class="mb-5 flex items-center gap-2 text-sm text-ink-600">
                <input type="checkbox" name="remember" class="h-4 w-4 rounded border-ink-300 text-saffron-500 focus:ring-saffron-400"> Remember me
            </label>
            <button class="btn-primary w-full">Sign in</button>
            <p class="mt-4 rounded-lg bg-ink-50 px-3 py-2 text-center text-xs text-ink-500">
                Demo login · <strong>admin@isl.test</strong> / <strong>password</strong>
            </p>
        </form>
        <a href="{{ route('home') }}" class="mt-5 block text-center text-sm text-ink-400 hover:text-white">← Back to public site</a>
    </div>
</body>
</html>
