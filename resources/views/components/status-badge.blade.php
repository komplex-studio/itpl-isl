@props(['status'])

@php
    $map = [
        'approved'   => ['Approved', 'bg-victory-500/10 text-victory-700'],
        'pending'    => ['Pending', 'bg-amber-100 text-amber-700'],
        'rejected'   => ['Rejected', 'bg-rose-100 text-rose-700'],
        'ongoing'    => ['Live now', 'bg-rose-100 text-rose-700'],
        'upcoming'   => ['Upcoming', 'bg-ink-100 text-ink-600'],
        'completed'  => ['Completed', 'bg-victory-500/10 text-victory-700'],
        'live'       => ['● Live', 'bg-rose-100 text-rose-700'],
        'scheduled'  => ['Scheduled', 'bg-ink-100 text-ink-600'],
    ];
    [$label, $cls] = $map[$status] ?? [ucfirst($status), 'bg-ink-100 text-ink-600'];
@endphp

<span {{ $attributes->merge(['class' => "chip $cls"]) }}>
    @if ($status === 'ongoing' || $status === 'live')
        <span class="inline-block h-1.5 w-1.5 animate-pulse rounded-full bg-rose-500"></span>
    @endif
    {{ $label }}
</span>
