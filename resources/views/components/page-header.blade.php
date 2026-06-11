@props(['eyebrow' => null, 'title', 'subtitle' => null, 'gradient' => 'from-ink-800 to-ink-950', 'image' => null])

<section class="relative overflow-hidden bg-cover bg-center bg-gradient-to-br {{ $gradient }} text-white"
    @if ($image) style="background-image:url('{{ $image }}');background-position:center 35%;" @endif>
    @if ($image)
        <div class="absolute inset-0 bg-gradient-to-r from-ink-950 via-ink-950/80 to-ink-950/40"></div>
        <div class="absolute inset-0 bg-gradient-to-t from-ink-950/80 to-transparent"></div>
    @endif
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
