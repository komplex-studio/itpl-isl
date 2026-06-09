@extends('layouts.public')
@section('title', 'Home')

@section('content')
    {{-- Hero --}}
    <section class="relative overflow-hidden bg-ink-950 text-white">
        <div class="absolute inset-0 bg-gradient-to-br {{ $featured?->gradient ?? 'from-ink-800 to-ink-950' }} opacity-90"></div>
        <div class="absolute -right-24 -top-24 h-96 w-96 rounded-full bg-saffron-500/20 blur-3xl"></div>
        <div class="absolute -bottom-32 left-1/4 h-96 w-96 rounded-full bg-victory-500/10 blur-3xl"></div>

        <div class="container-x relative grid gap-12 py-20 lg:grid-cols-12 lg:py-28">
            <div class="lg:col-span-7">
                <span class="chip bg-white/10 text-white backdrop-blur">
                    <span class="inline-block h-1.5 w-1.5 rounded-full bg-victory-400"></span>
                    Season 2026 · 8 disciplines · 28 states
                </span>
                <h1 class="mt-6 font-display text-5xl font-900 leading-[0.95] sm:text-6xl lg:text-7xl">
                    Where India<br><span class="text-saffron-400">competes.</span>
                </h1>
                <p class="mt-6 max-w-xl text-lg text-ink-200">
                    One platform for the nation's biggest multi-sport league — register as an athlete, follow live brackets and results, and download verified certificates.
                </p>
                <div class="mt-8 flex flex-wrap gap-3">
                    <a href="{{ route('register.create') }}" class="btn-primary">Register as athlete</a>
                    <a href="{{ route('schedule') }}" class="btn bg-white/10 px-6 py-3 text-white backdrop-blur transition hover:bg-white/20">View schedule</a>
                </div>
            </div>

            @if ($featured)
                <div class="lg:col-span-5">
                    <div class="rounded-3xl border border-white/15 bg-white/10 p-6 backdrop-blur-md">
                        <div class="flex items-center justify-between">
                            <span class="text-4xl">{{ $featured->sport->icon }}</span>
                            <x-status-badge :status="$featured->status" />
                        </div>
                        <p class="mt-4 text-xs font-bold uppercase tracking-widest text-saffron-300">{{ $featured->sport->name }}</p>
                        <h2 class="mt-1 font-display text-2xl font-800 leading-tight text-white">{{ $featured->name }}</h2>
                        <dl class="mt-5 grid grid-cols-2 gap-4 text-sm">
                            <div>
                                <dt class="text-ink-300">Venue</dt>
                                <dd class="font-semibold text-white">{{ $featured->city }}, {{ $featured->state }}</dd>
                            </div>
                            <div>
                                <dt class="text-ink-300">Dates</dt>
                                <dd class="font-semibold text-white">{{ $featured->date_range }}</dd>
                            </div>
                            <div>
                                <dt class="text-ink-300">Prize pool</dt>
                                <dd class="font-semibold text-white">₹{{ number_format($featured->prize_pool / 100000, 1) }}L</dd>
                            </div>
                            <div>
                                <dt class="text-ink-300">Format</dt>
                                <dd class="font-semibold capitalize text-white">{{ $featured->sport->format }}</dd>
                            </div>
                        </dl>
                        <a href="{{ route('events.show', $featured) }}" class="btn-primary btn-sm mt-6 w-full">Open event hub</a>
                    </div>
                </div>
            @endif
        </div>

        {{-- stat strip --}}
        <div class="relative border-t border-white/10 bg-ink-950/40 backdrop-blur">
            <div class="container-x grid grid-cols-2 gap-6 py-8 sm:grid-cols-4">
                @foreach ([['Registered athletes', $stats['athletes'].'+'], ['Live events', $stats['events']], ['Sports', $stats['sports']], ['Entries this season', $stats['registrations'].'+']] as [$label, $value])
                    <div>
                        <p class="font-display text-3xl font-900 text-saffron-400">{{ $value }}</p>
                        <p class="mt-1 text-sm text-ink-300">{{ $label }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Sports --}}
    <section class="container-x py-20">
        <div class="flex items-end justify-between">
            <div>
                <p class="eyebrow">Disciplines</p>
                <h2 class="mt-2 font-display text-3xl font-800 text-ink-900 sm:text-4xl">Eight sports, one league</h2>
            </div>
            <a href="{{ route('sports.index') }}" class="hidden text-sm font-semibold text-saffron-600 hover:text-saffron-700 sm:inline">All sports →</a>
        </div>

        <div class="mt-10 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
            @foreach ($sports as $sport)
                <a href="{{ route('sports.show', $sport) }}" class="card group p-6 transition hover:-translate-y-1 hover:shadow-lg">
                    <span class="text-4xl">{{ $sport->icon }}</span>
                    <h3 class="mt-4 font-display text-xl font-800 text-ink-900 group-hover:text-saffron-600">{{ $sport->name }}</h3>
                    <p class="mt-1 text-sm text-ink-500">{{ $sport->tagline }}</p>
                    <p class="mt-4 text-xs font-semibold uppercase tracking-wider text-ink-400">{{ $sport->events_count }} {{ Str::plural('event', $sport->events_count) }} · {{ ucfirst($sport->format) }}</p>
                </a>
            @endforeach
        </div>
    </section>

    {{-- Upcoming + Results --}}
    <section class="bg-white py-20">
        <div class="container-x grid gap-12 lg:grid-cols-2">
            <div>
                <p class="eyebrow">On the calendar</p>
                <h2 class="mt-2 font-display text-3xl font-800 text-ink-900">Upcoming events</h2>
                <div class="mt-8 space-y-4">
                    @foreach ($upcoming as $event)
                        <a href="{{ route('events.show', $event) }}" class="flex items-center gap-4 rounded-2xl border border-ink-100 p-4 transition hover:border-saffron-300 hover:bg-saffron-50/40">
                            <span class="grid h-14 w-14 shrink-0 place-items-center rounded-xl bg-ink-50 text-2xl">{{ $event->sport->icon }}</span>
                            <div class="min-w-0 flex-1">
                                <p class="truncate font-display text-base font-700 text-ink-900">{{ $event->name }}</p>
                                <p class="text-sm text-ink-500">{{ $event->city }}, {{ $event->state }} · {{ $event->date_range }}</p>
                            </div>
                            <x-status-badge :status="$event->status" />
                        </a>
                    @endforeach
                </div>
                <a href="{{ route('schedule') }}" class="btn-ghost btn-sm mt-6">Full schedule</a>
            </div>

            <div>
                <p class="eyebrow">From the arena</p>
                <h2 class="mt-2 font-display text-3xl font-800 text-ink-900">Latest results</h2>
                <div class="mt-8 space-y-4">
                    @forelse ($latestResults as $fixture)
                        <div class="rounded-2xl border border-ink-100 p-4">
                            <div class="flex items-center justify-between text-xs text-ink-400">
                                <span>{{ $fixture->event->sport->icon }} {{ $fixture->event->name }}</span>
                                <span>{{ $fixture->round }}</span>
                            </div>
                            <div class="mt-3 space-y-2">
                                @foreach ([[$fixture->athleteA, $fixture->score_a], [$fixture->athleteB, $fixture->score_b]] as [$ath, $score])
                                    @if ($ath)
                                        <div class="flex items-center gap-3 {{ $fixture->winner_id === $ath->id ? 'font-bold text-ink-900' : 'text-ink-500' }}">
                                            <x-avatar :athlete="$ath" size="sm" />
                                            <span class="flex-1 truncate">{{ $ath->name }}</span>
                                            @if ($fixture->winner_id === $ath->id)
                                                <span class="chip-victory">WON</span>
                                            @endif
                                            <span class="font-display text-lg font-800">{{ $score }}</span>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    @empty
                        <p class="text-sm text-ink-500">Results will appear here as events progress.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </section>

    {{-- Medal tally snapshot --}}
    <section class="container-x py-20">
        <div class="rounded-3xl bg-ink-950 p-8 text-white sm:p-12">
            <div class="flex flex-wrap items-end justify-between gap-4">
                <div>
                    <p class="eyebrow text-saffron-400">Medal tally</p>
                    <h2 class="mt-2 font-display text-3xl font-800">Leading states · Season 2026</h2>
                </div>
                <a href="{{ route('standings') }}" class="btn bg-white/10 px-5 py-2.5 text-sm text-white hover:bg-white/20">Full standings →</a>
            </div>

            <div class="mt-8 overflow-hidden rounded-2xl border border-white/10">
                <table class="w-full text-left text-sm">
                    <thead class="bg-white/5 text-xs uppercase tracking-wider text-ink-300">
                        <tr>
                            <th class="px-5 py-3">Rank</th>
                            <th class="px-5 py-3">State</th>
                            <th class="px-5 py-3 text-center">🥇</th>
                            <th class="px-5 py-3 text-center">🥈</th>
                            <th class="px-5 py-3 text-center">🥉</th>
                            <th class="px-5 py-3 text-right">Total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/5">
                        @foreach ($topStates as $i => $state)
                            <tr class="{{ $i === 0 ? 'bg-saffron-500/10' : '' }}">
                                <td class="px-5 py-3 font-display font-800 {{ $i === 0 ? 'text-saffron-400' : 'text-ink-300' }}">{{ $i + 1 }}</td>
                                <td class="px-5 py-3 font-semibold text-white">{{ $state->state }}</td>
                                <td class="px-5 py-3 text-center">{{ $state->gold }}</td>
                                <td class="px-5 py-3 text-center">{{ $state->silver }}</td>
                                <td class="px-5 py-3 text-center">{{ $state->bronze }}</td>
                                <td class="px-5 py-3 text-right font-display font-800 text-saffron-400">{{ $state->total }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    {{-- News --}}
    <section class="bg-white py-20">
        <div class="container-x">
            <div class="flex items-end justify-between">
                <div>
                    <p class="eyebrow">Newsroom</p>
                    <h2 class="mt-2 font-display text-3xl font-800 text-ink-900">Latest from the league</h2>
                </div>
                <a href="{{ route('news.index') }}" class="hidden text-sm font-semibold text-saffron-600 hover:text-saffron-700 sm:inline">All news →</a>
            </div>

            <div class="mt-10 grid gap-6 md:grid-cols-3">
                @foreach ($news as $article)
                    <a href="{{ route('news.show', $article) }}" class="card group overflow-hidden transition hover:-translate-y-1 hover:shadow-lg">
                        <div class="aspect-[16/9] bg-gradient-to-br {{ $article->gradient }} p-5">
                            <span class="chip bg-white/20 text-white backdrop-blur">{{ $article->category }}</span>
                        </div>
                        <div class="p-5">
                            <p class="text-xs text-ink-400">{{ $article->published_at->format('d M Y') }}</p>
                            <h3 class="mt-2 font-display text-lg font-700 leading-snug text-ink-900 group-hover:text-saffron-600">{{ $article->title }}</h3>
                            <p class="mt-2 line-clamp-2 text-sm text-ink-500">{{ $article->excerpt }}</p>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    {{-- CTA --}}
    <section class="container-x pb-20">
        <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-saffron-500 to-saffron-600 px-8 py-14 text-center text-white sm:px-16">
            <div class="absolute -right-16 -top-16 h-64 w-64 rounded-full bg-white/10 blur-2xl"></div>
            <h2 class="relative font-display text-3xl font-900 sm:text-4xl">Your podium starts with a registration</h2>
            <p class="relative mx-auto mt-3 max-w-2xl text-saffron-50">Create your athlete profile, get a unique ISL ID instantly, and enter any open event in minutes.</p>
            <a href="{{ route('register.create') }}" class="btn relative mt-8 bg-ink-950 px-8 py-3.5 text-white hover:bg-ink-900">Register now — it's free</a>
        </div>
    </section>
@endsection
