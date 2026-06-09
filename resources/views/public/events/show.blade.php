@extends('layouts.public')
@section('title', $event->name)

@section('content')
    {{-- Event hero --}}
    <section class="relative overflow-hidden bg-gradient-to-br {{ $event->gradient }} text-white">
        <div class="absolute -right-20 -top-24 h-80 w-80 rounded-full bg-saffron-500/20 blur-3xl"></div>
        <div class="container-x relative py-14">
            <a href="{{ route('schedule') }}" class="text-sm text-ink-200 hover:text-white">← Back to schedule</a>
            <div class="mt-5 flex flex-wrap items-start justify-between gap-4">
                <div>
                    <div class="flex items-center gap-3">
                        <span class="text-4xl">{{ $event->sport->icon }}</span>
                        <span class="text-xs font-bold uppercase tracking-widest text-saffron-300">{{ $event->sport->name }}</span>
                        <x-status-badge :status="$event->status" />
                    </div>
                    <h1 class="mt-3 font-display text-4xl font-900 sm:text-5xl">{{ $event->name }}</h1>
                    <p class="mt-2 text-ink-200">{{ $event->venue }} · {{ $event->city }}, {{ $event->state }}</p>
                </div>
                @if ($event->registration_open)
                    <a href="{{ route('register.create') }}" class="btn-primary">Register for this event</a>
                @endif
            </div>

            <dl class="mt-10 grid grid-cols-2 gap-6 sm:grid-cols-4">
                @foreach ([
                    ['Dates', $event->date_range],
                    ['Prize pool', '₹'.number_format($event->prize_pool / 100000, 1).'L'],
                    ['Participants', $participants->count()],
                    ['Format', ucfirst($event->sport->format)],
                ] as [$label, $value])
                    <div class="rounded-2xl border border-white/15 bg-white/10 p-4 backdrop-blur">
                        <dt class="text-xs uppercase tracking-wider text-ink-300">{{ $label }}</dt>
                        <dd class="mt-1 font-display text-xl font-800">{{ $value }}</dd>
                    </div>
                @endforeach
            </dl>
        </div>
    </section>

    <section class="container-x grid gap-10 py-14 lg:grid-cols-3">
        {{-- Main column --}}
        <div class="space-y-10 lg:col-span-2">
            <div>
                <h2 class="font-display text-2xl font-800 text-ink-900">About the event</h2>
                <p class="mt-3 leading-relaxed text-ink-600">{{ $event->summary }}</p>
            </div>

            @if ($event->fixtures->isNotEmpty())
                <div>
                    <div class="flex items-center justify-between">
                        <h2 class="font-display text-2xl font-800 text-ink-900">Fixtures & results</h2>
                        <a href="{{ route('events.bracket', $event) }}" class="text-sm font-semibold text-saffron-600 hover:text-saffron-700">View bracket →</a>
                    </div>
                    <div class="mt-5 space-y-3">
                        @foreach ($event->fixtures as $fx)
                            <div class="card flex items-center gap-4 p-4">
                                <div class="w-28 shrink-0">
                                    <p class="text-xs font-bold uppercase tracking-wider text-ink-400">{{ $fx->round }}</p>
                                    <p class="text-xs text-ink-400">{{ $fx->scheduled_at?->format('d M, H:i') }}</p>
                                </div>
                                <div class="flex-1 space-y-1.5">
                                    @foreach ([[$fx->athleteA, $fx->score_a], [$fx->athleteB, $fx->score_b]] as [$ath, $score])
                                        <div class="flex items-center gap-2 {{ $fx->winner_id && $fx->winner_id === $ath?->id ? 'font-bold text-ink-900' : 'text-ink-500' }}">
                                            <span class="flex-1 truncate">{{ $ath?->name ?? 'TBD' }}</span>
                                            <span class="font-display font-800">{{ $score ?? '–' }}</span>
                                        </div>
                                    @endforeach
                                </div>
                                <x-status-badge :status="$fx->status" />
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        {{-- Sidebar: participants --}}
        <aside>
            <div class="card sticky top-24 p-6">
                <h3 class="font-display text-lg font-800 text-ink-900">Confirmed athletes</h3>
                <p class="text-sm text-ink-500">{{ $participants->count() }} approved entries</p>
                <div class="mt-5 space-y-3">
                    @forelse ($participants->take(12) as $athlete)
                        <div class="flex items-center gap-3">
                            <x-avatar :athlete="$athlete" size="sm" />
                            <div class="min-w-0">
                                <p class="truncate text-sm font-semibold text-ink-800">{{ $athlete->name }}</p>
                                <p class="text-xs text-ink-400">{{ $athlete->state }} · {{ $athlete->code }}</p>
                            </div>
                        </div>
                    @empty
                        <p class="text-sm text-ink-500">Athlete list will appear once entries are approved.</p>
                    @endforelse
                </div>
            </div>
        </aside>
    </section>
@endsection
