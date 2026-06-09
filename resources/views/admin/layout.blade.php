<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Dashboard') · ISL Admin</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-ink-100">
@php
    $nav = [
        ['Dashboard', 'admin.dashboard', '▦'],
        ['Registrations', 'admin.registrations.index', '📝'],
        ['Events', 'admin.events.index', '📅'],
        ['Fixtures & results', 'admin.fixtures.index', '🥊'],
        ['Athletes', 'admin.athletes.index', '🪪'],
        ['Sports', 'admin.sports.index', '🏅'],
        ['Certificates', 'admin.certificates.index', '🎖️'],
        ['News', 'admin.news.index', '📰'],
    ];
@endphp

<div x-data="{ open: false }" class="flex min-h-screen">
    {{-- Sidebar --}}
    <aside class="fixed inset-y-0 left-0 z-40 w-64 -translate-x-full bg-ink-950 text-ink-300 transition lg:static lg:translate-x-0"
           :class="open && '!translate-x-0'">
        <div class="flex h-16 items-center gap-2.5 border-b border-white/10 px-5">
            <span class="grid h-9 w-9 place-items-center rounded-lg bg-gradient-to-br from-saffron-500 to-saffron-700 font-display text-sm font-900 text-white">IS<span class="text-victory-400">L</span></span>
            <div class="leading-tight">
                <p class="font-display text-sm font-800 text-white">ISL Admin</p>
                <p class="text-[10px] uppercase tracking-widest text-ink-500">Organiser console</p>
            </div>
        </div>
        <nav class="space-y-1 p-3">
            @foreach ($nav as [$label, $route, $icon])
                <a href="{{ route($route) }}"
                   class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-semibold transition {{ request()->routeIs($route) ? 'bg-saffron-500 text-white' : 'text-ink-300 hover:bg-white/5 hover:text-white' }}">
                    <span class="text-base">{{ $icon }}</span>{{ $label }}
                </a>
            @endforeach
        </nav>
        <div class="absolute inset-x-0 bottom-0 border-t border-white/10 p-3">
            <a href="{{ route('home') }}" class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm text-ink-400 hover:text-white">↗ View public site</a>
        </div>
    </aside>

    <div x-show="open" x-cloak @click="open = false" class="fixed inset-0 z-30 bg-black/40 lg:hidden"></div>

    {{-- Main --}}
    <div class="flex flex-1 flex-col">
        <header class="sticky top-0 z-20 flex h-16 items-center justify-between border-b border-ink-200 bg-white px-5">
            <div class="flex items-center gap-3">
                <button @click="open = !open" class="grid h-9 w-9 place-items-center rounded-lg border border-ink-200 lg:hidden">☰</button>
                <h1 class="font-display text-lg font-800 text-ink-900">@yield('heading', 'Dashboard')</h1>
            </div>
            <div class="flex items-center gap-3">
                <div class="hidden text-right sm:block">
                    <p class="text-sm font-semibold text-ink-900">{{ auth()->user()->name }}</p>
                    <p class="text-xs text-ink-400">{{ auth()->user()->email }}</p>
                </div>
                <span class="grid h-9 w-9 place-items-center rounded-full bg-ink-900 font-display text-sm font-800 text-white">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</span>
                <form method="POST" action="{{ route('admin.logout') }}">
                    @csrf
                    <button class="btn-ghost btn-sm">Logout</button>
                </form>
            </div>
        </header>

        <main class="flex-1 p-5 sm:p-7">
            @if (session('flash'))
                <div class="mb-5 rounded-xl border border-victory-500/30 bg-victory-500/10 px-4 py-3 text-sm font-semibold text-victory-700">
                    {{ session('flash') }}
                </div>
            @endif
            @yield('content')
        </main>
    </div>
</div>
</body>
</html>
