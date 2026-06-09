@props(['athlete', 'size' => 'md'])

@php
    $sizes = [
        'sm' => 'h-9 w-9 text-xs',
        'md' => 'h-11 w-11 text-sm',
        'lg' => 'h-16 w-16 text-lg',
        'xl' => 'h-24 w-24 text-2xl',
    ];
    $cls = $sizes[$size] ?? $sizes['md'];
    $tint = $athlete->avatar_tint ?? 'from-saffron-400 to-saffron-600';
@endphp

<span {{ $attributes->merge(['class' => "grid shrink-0 place-items-center rounded-full bg-gradient-to-br $tint font-display font-800 text-white $cls"]) }}>
    {{ $athlete->initials }}
</span>
