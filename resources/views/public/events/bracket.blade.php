@extends('layouts.public')
@section('title', $event->name.' · Bracket')

@section('content')
    <x-page-header :eyebrow="$event->sport->name.' · knockout'" :title="$event->name" :gradient="$event->gradient">
        <a href="{{ route('events.show', $event) }}" class="btn bg-white/10 px-5 py-2.5 text-sm text-white hover:bg-white/20">← Event hub</a>
    </x-page-header>

    <section class="py-14">
        <div class="container-x">
            <h2 class="font-display text-2xl font-800 text-ink-900">Single-elimination bracket</h2>
            <p class="mt-1 text-sm text-ink-500">Winners advance left to right. Scroll horizontally to follow the draw to the final.</p>
        </div>

        <div class="mt-8 overflow-x-auto pb-6">
            <div class="container-x flex min-w-max gap-8">
                @foreach ($rounds as $order => $fixtures)
                    <div class="flex w-72 flex-col">
                        <h3 class="mb-4 text-center text-xs font-bold uppercase tracking-widest text-ink-400">
                            {{ $fixtures->first()->round }}
                        </h3>
                        <div class="flex flex-1 flex-col justify-around gap-6">
                            @foreach ($fixtures as $fx)
                                <div class="card overflow-hidden {{ $fx->status === 'live' ? 'ring-2 ring-rose-400' : '' }}">
                                    <div class="flex items-center justify-between border-b border-ink-100 px-4 py-2 text-xs text-ink-400">
                                        <span>{{ $fx->scheduled_at?->format('d M · H:i') }}</span>
                                        <x-status-badge :status="$fx->status" class="!px-2 !py-0.5" />
                                    </div>
                                    @foreach ([[$fx->athleteA, $fx->score_a], [$fx->athleteB, $fx->score_b]] as [$ath, $score])
                                        <div class="flex items-center gap-2 px-4 py-2.5 {{ $fx->winner_id && $fx->winner_id === $ath?->id ? 'bg-victory-500/10' : '' }}">
                                            @if ($ath)
                                                <x-avatar :athlete="$ath" size="sm" />
                                                <span class="flex-1 truncate text-sm {{ $fx->winner_id === $ath->id ? 'font-bold text-ink-900' : 'text-ink-600' }}">{{ $ath->name }}</span>
                                                @if ($fx->winner_id === $ath->id)
                                                    <span class="text-victory-600">✓</span>
                                                @endif
                                                <span class="font-display text-base font-800 text-ink-900">{{ $score ?? '–' }}</span>
                                            @else
                                                <span class="grid h-9 w-9 shrink-0 place-items-center rounded-full bg-ink-100 text-xs text-ink-400">?</span>
                                                <span class="flex-1 text-sm italic text-ink-400">Awaiting winner</span>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach

                {{-- Champion column --}}
                @php $final = $rounds->last()?->first(); @endphp
                <div class="flex w-60 flex-col justify-center">
                    <h3 class="mb-4 text-center text-xs font-bold uppercase tracking-widest text-saffron-500">Champion</h3>
                    <div class="rounded-2xl bg-gradient-to-br from-saffron-500 to-saffron-700 p-6 text-center text-white shadow-lg shadow-saffron-500/30">
                        <p class="text-3xl">🏆</p>
                        @if ($final?->winner)
                            <x-avatar :athlete="$final->winner" size="lg" class="mx-auto mt-3 ring-4 ring-white/30" />
                            <p class="mt-3 font-display text-lg font-800">{{ $final->winner->name }}</p>
                            <p class="text-sm text-saffron-100">{{ $final->winner->state }}</p>
                        @else
                            <p class="mt-4 text-sm text-saffron-100">To be decided in the final</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
