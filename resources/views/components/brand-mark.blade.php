@props(['dark' => false])

<a href="{{ route('home') }}" {{ $attributes->merge(['class' => 'inline-flex items-center gap-2.5']) }}>
    <span class="grid h-10 w-10 place-items-center rounded-xl bg-gradient-to-br from-saffron-500 to-saffron-700 font-display text-lg font-900 leading-none text-white shadow-lg shadow-saffron-500/30">
        IS<span class="text-victory-400">L</span>
    </span>
    <span class="leading-tight">
        <span class="block font-display text-lg font-800 {{ $dark ? 'text-white' : 'text-ink-900' }}">Indian Sports League</span>
        <span class="block text-[11px] font-semibold uppercase tracking-[0.18em] {{ $dark ? 'text-ink-300' : 'text-ink-400' }}">Season 2026</span>
    </span>
</a>
