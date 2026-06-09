@extends('layouts.public')
@section('title', 'Schedule & Fixtures')

@section('content')
    <x-page-header
        eyebrow="Season 2026"
        title="Schedule & fixtures"
        subtitle="Every event across all eight disciplines — filter by sport or status and dive into live brackets." />

    <section class="container-x py-12">
        {{-- Filters --}}
        <form method="GET" class="flex flex-wrap items-end gap-3">
            <div>
                <label class="label">Sport</label>
                <select name="sport" class="input min-w-44" onchange="this.form.submit()">
                    <option value="">All sports</option>
                    @foreach ($sports as $sport)
                        <option value="{{ $sport->slug }}" @selected(($filters['sport'] ?? '') === $sport->slug)>{{ $sport->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="label">Status</label>
                <select name="status" class="input min-w-44" onchange="this.form.submit()">
                    <option value="">Any status</option>
                    @foreach (['ongoing' => 'Live now', 'upcoming' => 'Upcoming', 'completed' => 'Completed'] as $val => $lbl)
                        <option value="{{ $val }}" @selected(($filters['status'] ?? '') === $val)>{{ $lbl }}</option>
                    @endforeach
                </select>
            </div>
            @if (array_filter($filters))
                <a href="{{ route('schedule') }}" class="btn-ghost btn-sm">Clear</a>
            @endif
            <p class="ml-auto self-center text-sm text-ink-500">{{ $events->count() }} {{ Str::plural('event', $events->count()) }}</p>
        </form>

        {{-- Event list --}}
        <div class="mt-8 space-y-4">
            @forelse ($events as $event)
                <a href="{{ route('events.show', $event) }}" class="card flex flex-col gap-4 p-5 transition hover:border-saffron-300 hover:shadow-md sm:flex-row sm:items-center">
                    <div class="flex items-center gap-4 sm:w-64">
                        <span class="grid h-14 w-14 shrink-0 place-items-center rounded-xl bg-ink-50 text-2xl">{{ $event->sport->icon }}</span>
                        <div>
                            <p class="text-xs font-bold uppercase tracking-wider text-ink-400">{{ $event->sport->name }}</p>
                            <p class="font-display text-base font-700 text-ink-900">{{ $event->name }}</p>
                        </div>
                    </div>
                    <div class="grid flex-1 grid-cols-2 gap-3 text-sm sm:grid-cols-3">
                        <div><p class="text-ink-400">Dates</p><p class="font-semibold text-ink-800">{{ $event->date_range }}</p></div>
                        <div><p class="text-ink-400">Venue</p><p class="font-semibold text-ink-800">{{ $event->city }}, {{ $event->state }}</p></div>
                        <div class="hidden sm:block"><p class="text-ink-400">Prize pool</p><p class="font-semibold text-ink-800">₹{{ number_format($event->prize_pool / 100000, 1) }}L</p></div>
                    </div>
                    <x-status-badge :status="$event->status" />
                </a>
            @empty
                <div class="card p-10 text-center text-ink-500">No events match these filters.</div>
            @endforelse
        </div>
    </section>

    {{-- Brackets --}}
    <section id="brackets" class="bg-white py-16">
        <div class="container-x">
            <p class="eyebrow">Knockout</p>
            <h2 class="mt-2 font-display text-3xl font-800 text-ink-900">Live brackets</h2>
            <p class="mt-2 max-w-2xl text-ink-500">Follow the road to the final. Tap any event to open its full single-elimination bracket.</p>

            <div class="mt-8 grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
                @forelse ($bracketEvents as $event)
                    <a href="{{ route('events.bracket', $event) }}" class="card group p-6 transition hover:-translate-y-1 hover:shadow-lg">
                        <div class="flex items-center justify-between">
                            <span class="text-3xl">{{ $event->sport->icon }}</span>
                            <x-status-badge :status="$event->status" />
                        </div>
                        <h3 class="mt-4 font-display text-lg font-700 text-ink-900 group-hover:text-saffron-600">{{ $event->name }}</h3>
                        <p class="mt-1 text-sm text-ink-500">{{ $event->city }}, {{ $event->state }}</p>
                        <p class="mt-4 text-sm font-semibold text-saffron-600">Open bracket →</p>
                    </a>
                @empty
                    <p class="text-ink-500">Brackets are published once draws are finalised.</p>
                @endforelse
            </div>
        </div>
    </section>
@endsection
