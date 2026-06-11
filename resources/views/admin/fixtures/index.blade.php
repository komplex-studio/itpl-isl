@extends('admin.layout')
@section('title', 'Fixtures & Results')
@section('heading', 'Fixtures & results')

@section('content')
    <div class="mb-5 flex items-center gap-3">
        <form method="GET" class="flex-1">
            <select name="event" class="input min-w-64 py-2 text-sm" onchange="this.form.submit()">
                <option value="">All events with fixtures</option>
                @foreach ($events as $event)
                    <option value="{{ $event->id }}" @selected((string)($filters['event'] ?? '') === (string)$event->id)>{{ $event->sport->icon }} {{ $event->name }}</option>
                @endforeach
            </select>
        </form>
        <a href="{{ route('admin.fixtures.create') }}" class="btn-primary btn-sm shrink-0">+ New fixture</a>
    </div>

    <div class="space-y-4">
        @forelse ($fixtures as $fx)
            <div class="card p-5">
                <div class="flex flex-wrap items-center justify-between gap-2">
                    <div class="flex items-center gap-3">
                        <span class="chip-ink">{{ $fx->event->sport->icon }} {{ $fx->event->name }}</span>
                        <span class="text-xs font-bold uppercase tracking-wider text-ink-400">{{ $fx->round }}</span>
                    </div>
                    <div class="flex items-center gap-3 text-xs text-ink-400">
                        <span>{{ $fx->scheduled_at?->format('d M Y · H:i') }}</span>
                        <x-status-badge :status="$fx->status" />
                        <a href="{{ route('admin.fixtures.edit', $fx) }}" class="chip border border-ink-200 bg-white text-ink-700 hover:bg-ink-50">Edit</a>
                        <x-admin.delete-button :action="route('admin.fixtures.destroy', $fx)" label="Delete this fixture?" />
                    </div>
                </div>

                <form method="POST" action="{{ route('admin.fixtures.result', $fx) }}" class="mt-4 grid gap-4 sm:grid-cols-12 sm:items-end">
                    @csrf @method('PATCH')

                    {{-- Athlete A --}}
                    <div class="sm:col-span-4">
                        <div class="flex items-center gap-2">
                            @if ($fx->athleteA) <x-avatar :athlete="$fx->athleteA" size="sm" /> @endif
                            <span class="truncate text-sm font-semibold text-ink-800">{{ $fx->athleteA?->name ?? 'TBD' }}</span>
                        </div>
                        <input name="score_a" value="{{ $fx->score_a }}" placeholder="Score" class="input mt-2 py-1.5 text-sm">
                    </div>

                    <div class="text-center text-sm font-bold text-ink-400 sm:col-span-1">vs</div>

                    {{-- Athlete B --}}
                    <div class="sm:col-span-4">
                        <div class="flex items-center gap-2">
                            @if ($fx->athleteB) <x-avatar :athlete="$fx->athleteB" size="sm" /> @endif
                            <span class="truncate text-sm font-semibold text-ink-800">{{ $fx->athleteB?->name ?? 'TBD' }}</span>
                        </div>
                        <input name="score_b" value="{{ $fx->score_b }}" placeholder="Score" class="input mt-2 py-1.5 text-sm">
                    </div>

                    {{-- Winner + save --}}
                    <div class="sm:col-span-3">
                        <select name="winner_id" class="input py-1.5 text-sm">
                            <option value="">Winner…</option>
                            @if ($fx->athleteA)<option value="{{ $fx->athlete_a_id }}" @selected($fx->winner_id === $fx->athlete_a_id)>{{ $fx->athleteA->name }}</option>@endif
                            @if ($fx->athleteB)<option value="{{ $fx->athlete_b_id }}" @selected($fx->winner_id === $fx->athlete_b_id)>{{ $fx->athleteB->name }}</option>@endif
                        </select>
                        <button class="btn-dark btn-sm mt-2 w-full">Save result</button>
                    </div>
                </form>
            </div>
        @empty
            <div class="card p-10 text-center text-ink-500">No fixtures yet. Publish a draw to populate results here.</div>
        @endforelse
    </div>
@endsection
