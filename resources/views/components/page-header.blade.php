@props(['eyebrow' => null, 'title', 'subtitle' => null, 'gradient' => 'from-ink-800 to-ink-950'])

<section class="relative overflow-hidden bg-gradient-to-br {{ $gradient }} text-white">
    <div class="absolute -right-20 -top-20 h-72 w-72 rounded-full bg-saffron-500/15 blur-3xl"></div>
    <div class="container-x relative py-14 sm:py-16">
        @if ($eyebrow)
            <p class="text-xs font-bold uppercase tracking-[0.2em] text-saffron-300">{{ $eyebrow }}</p>
        @endif
        <h1 class="mt-2 font-display text-4xl font-900 leading-tight sm:text-5xl">{{ $title }}</h1>
        @if ($subtitle)
            <p class="mt-3 max-w-2xl text-ink-200">{{ $subtitle }}</p>
        @endif
        @if (isset($slot) && trim($slot))
            <div class="mt-6">{{ $slot }}</div>
        @endif
    </div>
</section>
