@php
    $links = [
        ['Sports', route('sports.index')],
        ['Schedule', route('schedule')],
        ['Brackets', route('schedule').'#brackets'],
        ['Standings', route('standings')],
        ['News', route('news.index')],
        ['Certificates', route('certificates.index')],
    ];
@endphp

<header x-data="{ open: false }" class="sticky top-0 z-50 border-b border-ink-100 bg-white/85 backdrop-blur-md">
    <nav class="container-x flex h-18 items-center justify-between py-3">
        <x-brand-mark />

        <div class="hidden items-center gap-1 lg:flex">
            @foreach ($links as [$label, $url])
                <a href="{{ $url }}"
                   class="rounded-full px-3.5 py-2 text-sm font-semibold text-ink-600 transition hover:bg-ink-50 hover:text-ink-900">
                    {{ $label }}
                </a>
            @endforeach
        </div>

        <div class="flex items-center gap-2">
            <a href="{{ route('register.create') }}" class="btn-primary btn-sm hidden sm:inline-flex">
                Register as athlete
            </a>
            <a href="{{ route('admin.login') }}" class="btn-ghost btn-sm hidden md:inline-flex">Admin</a>
            <button @click="open = !open" class="grid h-10 w-10 place-items-center rounded-xl border border-ink-200 lg:hidden" aria-label="Menu">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" d="M4 7h16M4 12h16M4 17h16"/></svg>
            </button>
        </div>
    </nav>

    <div x-show="open" x-cloak class="border-t border-ink-100 bg-white lg:hidden">
        <div class="container-x grid gap-1 py-4">
            @foreach ($links as [$label, $url])
                <a href="{{ $url }}" class="rounded-lg px-3 py-2.5 text-sm font-semibold text-ink-700 hover:bg-ink-50">{{ $label }}</a>
            @endforeach
            <a href="{{ route('register.create') }}" class="btn-primary mt-2">Register as athlete</a>
            <a href="{{ route('admin.login') }}" class="btn-ghost mt-1">Admin panel</a>
        </div>
    </div>
</header>
