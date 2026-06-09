@extends('layouts.public')
@section('title', 'Medal Tally')

@section('content')
    <x-page-header
        eyebrow="Standings"
        title="National medal tally"
        subtitle="State-by-state medal standings across all disciplines in the 2026 season."
        gradient="from-saffron-600 to-ink-950" />

    <section class="container-x py-14">
        {{-- Totals --}}
        <div class="grid grid-cols-3 gap-4 sm:max-w-xl">
            @foreach ([['🥇 Gold', $totals['gold']], ['🥈 Silver', $totals['silver']], ['🥉 Bronze', $totals['bronze']]] as [$label, $value])
                <div class="card p-5 text-center">
                    <p class="font-display text-3xl font-900 text-ink-900">{{ $value }}</p>
                    <p class="mt-1 text-sm text-ink-500">{{ $label }}</p>
                </div>
            @endforeach
        </div>

        {{-- Table --}}
        <div class="card mt-8 overflow-hidden">
            <table class="w-full text-left text-sm">
                <thead class="bg-ink-50 text-xs uppercase tracking-wider text-ink-500">
                    <tr>
                        <th class="px-5 py-3.5">Rank</th>
                        <th class="px-5 py-3.5">State / UT</th>
                        <th class="px-5 py-3.5 text-center">🥇</th>
                        <th class="px-5 py-3.5 text-center">🥈</th>
                        <th class="px-5 py-3.5 text-center">🥉</th>
                        <th class="px-5 py-3.5 text-right">Total</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-ink-100">
                    @foreach ($tally as $i => $row)
                        <tr class="{{ $i < 3 ? 'bg-saffron-50/40' : '' }} transition hover:bg-ink-50">
                            <td class="px-5 py-3.5">
                                <span class="grid h-8 w-8 place-items-center rounded-full font-display text-sm font-800 {{ $i === 0 ? 'bg-saffron-500 text-white' : ($i < 3 ? 'bg-saffron-100 text-saffron-700' : 'bg-ink-100 text-ink-500') }}">{{ $i + 1 }}</span>
                            </td>
                            <td class="px-5 py-3.5 font-semibold text-ink-900">{{ $row->state }}</td>
                            <td class="px-5 py-3.5 text-center text-ink-700">{{ $row->gold }}</td>
                            <td class="px-5 py-3.5 text-center text-ink-700">{{ $row->silver }}</td>
                            <td class="px-5 py-3.5 text-center text-ink-700">{{ $row->bronze }}</td>
                            <td class="px-5 py-3.5 text-right font-display font-800 text-ink-900">{{ $row->total }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </section>
@endsection
