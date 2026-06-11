@extends('layouts.public')
@section('title', 'Home')

@section('content')
    @php
        // Approved hero photograph from the landing-image concept (athletics, full-bleed, high-res).
        $heroImage = 'https://images.unsplash.com/photo-1552674605-db6ffd4facb5?auto=format&fit=crop&w=2400&q=85';
    @endphp
    {{-- Hero — full-bleed action photo --}}
    <section class="relative isolate overflow-hidden bg-ink-950 text-white">
        {{-- photograph (gradient shows through if the image fails to load) --}}
        <div class="absolute inset-0 scale-105 bg-cover bg-gradient-to-br from-ink-800 to-ink-950"
             style="background-image:url('{{ $heroImage }}');background-position:center 30%;"></div>
        {{-- lighter scrims: keep the left readable for text, let the photo show through on the right --}}
        <div class="absolute inset-0 bg-gradient-to-r from-ink-950 via-ink-950/60 to-ink-950/5"></div>
        <div class="absolute inset-0 bg-gradient-to-t from-ink-950 via-ink-950/10 to-transparent"></div>
        {{-- accent glows --}}
        <div class="pointer-events-none absolute -left-32 top-1/3 h-[34rem] w-[34rem] rounded-full bg-saffron-500/20 blur-[120px]"></div>
        <div class="pointer-events-none absolute right-0 top-0 h-96 w-96 rounded-full bg-victory-500/10 blur-[120px]"></div>

        <div class="container-x relative flex min-h-[82vh] flex-col justify-center py-24">
            <span class="chip w-fit border border-white/15 bg-white/5 text-white backdrop-blur-md">
                <span class="relative flex h-2 w-2">
                    <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-victory-400 opacity-75"></span>
                    <span class="relative inline-flex h-2 w-2 rounded-full bg-victory-400"></span>
                </span>
                Season 2026 · {{ $stats['sports'] }} disciplines · 28 states
            </span>
            <h1 class="mt-7 max-w-3xl font-display text-6xl font-900 leading-[0.9] [text-shadow:0_2px_40px_rgba(0,0,0,0.5)] sm:text-7xl lg:text-8xl">
                Where India<br><span class="bg-gradient-to-r from-saffron-300 via-saffron-400 to-saffron-500 bg-clip-text text-transparent">competes.</span>
            </h1>
            <p class="mt-6 max-w-xl text-lg leading-relaxed text-white/75">
                One platform for the nation's biggest multi-sport league — register as an athlete, follow live brackets and results, and download verified certificates.
            </p>
            <div class="mt-9 flex flex-wrap gap-3">
                <a href="{{ route('register.create') }}" class="btn-primary group">
                    Register as athlete
                    <span class="transition group-hover:translate-x-0.5">→</span>
                </a>
                <a href="{{ route('schedule') }}" class="btn border border-white/15 bg-white/5 px-6 py-3 text-white backdrop-blur-md transition hover:border-white/30 hover:bg-white/10">View schedule</a>
            </div>

            {{-- stat strip --}}
            <div class="mt-16 grid max-w-3xl grid-cols-2 gap-x-6 gap-y-8 border-t border-white/10 pt-8 sm:grid-cols-4">
                @foreach ([['Registered athletes', $stats['athletes'].'+'], ['Live events', $stats['events']], ['Sports', $stats['sports']], ['Entries this season', $stats['registrations'].'+']] as [$label, $value])
                    <div>
                        <p class="font-display text-4xl font-900 text-saffron-400 [text-shadow:0_2px_30px_rgba(255,107,26,0.3)]">{{ $value }}</p>
                        <p class="mt-1 text-sm font-medium text-white/55">{{ $label }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Sports — photo cards --}}
    <section class="bg-ink-50 py-20">
        <div class="container-x">
            <div class="flex items-end justify-between">
                <div>
                    <p class="eyebrow">Disciplines</p>
                    <h2 class="mt-2 font-display text-3xl font-800 text-ink-900 sm:text-4xl">Eight sports, one league</h2>
                </div>
                <a href="{{ route('sports.index') }}" class="hidden text-sm font-semibold text-saffron-600 hover:text-saffron-700 sm:inline">All sports →</a>
            </div>

            <div class="mt-10 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                @foreach ($sports as $sport)
                    <a href="{{ route('sports.show', $sport) }}" class="group relative block h-64 overflow-hidden rounded-3xl bg-gradient-to-br from-ink-700 to-ink-950">
                        <div class="absolute inset-0 bg-cover bg-center transition duration-500 group-hover:scale-105"
                             @if ($sport->image) style="background-image:url('{{ $sport->image }}');" @endif></div>
                        <div class="absolute inset-0 bg-gradient-to-t from-ink-950/90 via-ink-950/20 to-transparent"></div>
                        <div class="absolute inset-x-0 bottom-0 p-5">
                            <h3 class="font-display text-xl font-800 text-white">{{ $sport->icon }} {{ $sport->name }}</h3>
                            <p class="mt-0.5 text-xs font-semibold uppercase tracking-wider text-white/60">{{ ucfirst($sport->format) }}</p>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Featured split banner --}}
    @if ($featured)
        <section class="bg-ink-50 pb-20">
            <div class="container-x">
                <div class="grid items-stretch overflow-hidden rounded-3xl bg-ink-950 text-white lg:grid-cols-2">
                    <div class="relative min-h-[22rem] bg-cover bg-center bg-gradient-to-br {{ $featured->gradient }}"
                         @if ($featured->image) style="background-image:url('{{ $featured->image }}');" @endif>
                        <div class="absolute inset-0 bg-gradient-to-t from-ink-950/60 to-transparent lg:bg-gradient-to-l"></div>
                        <span class="absolute left-6 top-6"><x-status-badge :status="$featured->status" /></span>
                    </div>
                    <div class="flex flex-col justify-center p-8 sm:p-12">
                        <p class="text-sm font-bold uppercase tracking-[0.18em] text-saffron-400">Featured event · {{ $featured->sport->name }}</p>
                        <h2 class="mt-2 font-display text-3xl font-800 leading-tight sm:text-4xl">{{ $featured->name }}</h2>
                        <dl class="mt-8 grid grid-cols-2 gap-6 text-sm">
                            <div><dt class="text-white/50">Venue</dt><dd class="mt-0.5 font-semibold">{{ $featured->city }}, {{ $featured->state }}</dd></div>
                            <div><dt class="text-white/50">Dates</dt><dd class="mt-0.5 font-semibold">{{ $featured->date_range }}</dd></div>
                            <div><dt class="text-white/50">Prize pool</dt><dd class="mt-0.5 font-semibold">₹{{ number_format($featured->prize_pool / 100000, 1) }}L</dd></div>
                            <div><dt class="text-white/50">Format</dt><dd class="mt-0.5 font-semibold capitalize">{{ $featured->sport->format }}</dd></div>
                        </dl>
                        <a href="{{ route('events.show', $featured) }}" class="btn-primary mt-9 w-fit">Open event hub →</a>
                    </div>
                </div>
            </div>
        </section>
    @endif

    {{-- Upcoming + Results --}}
    <section class="bg-white py-20">
        <div class="container-x grid gap-12 lg:grid-cols-2">
            <div>
                <p class="eyebrow">On the calendar</p>
                <h2 class="mt-2 font-display text-3xl font-800 text-ink-900">Upcoming events</h2>
                <div class="mt-8 space-y-4">
                    @foreach ($upcoming as $event)
                        <a href="{{ route('events.show', $event) }}" class="flex items-center gap-4 rounded-2xl border border-ink-100 p-4 transition hover:border-saffron-300 hover:bg-saffron-50/40">
                            <span class="h-14 w-14 shrink-0 overflow-hidden rounded-xl bg-cover bg-center bg-gradient-to-br {{ $event->gradient }}"
                                  @if ($event->image) style="background-image:url('{{ $event->image }}');" @endif></span>
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
    <section class="bg-ink-50 py-20">
        <div class="container-x">
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
                        <div class="relative aspect-[16/9] bg-cover bg-center bg-gradient-to-br {{ $article->gradient }}"
                             @if ($article->image) style="background-image:url('{{ $article->image }}');" @endif>
                            <span class="absolute left-4 top-4 chip bg-white/20 text-white backdrop-blur">{{ $article->category }}</span>
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
    <section class="bg-white pb-20">
        <div class="container-x">
            <div class="relative overflow-hidden rounded-3xl">
                <div class="absolute inset-0 bg-cover bg-center bg-gradient-to-r from-saffron-500 to-saffron-700"
                     style="background-image:url('{{ $featured?->sport->image ?? '' }}');"></div>
                <div class="absolute inset-0 bg-saffron-600/85"></div>
                <div class="relative px-8 py-16 text-center text-white sm:px-16">
                    <h2 class="font-display text-3xl font-900 sm:text-4xl">Your podium starts with a registration</h2>
                    <p class="mx-auto mt-3 max-w-2xl text-saffron-50">Create your athlete profile, get a unique ISL ID instantly, and enter any open event in minutes.</p>
                    <a href="{{ route('register.create') }}" class="btn relative mt-8 bg-ink-950 px-8 py-3.5 text-white hover:bg-ink-900">Register now — it's free</a>
                </div>
            </div>
        </div>
    </section>
@endsection
