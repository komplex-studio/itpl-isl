@extends('admin.layout')
@section('title', 'Dashboard')
@section('heading', 'Dashboard')

@section('content')
    {{-- Stat cards --}}
    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
        @foreach ([
            ['Registered athletes', $stats['athletes'], '🪪', 'from-saffron-400 to-saffron-600'],
            ['Active events', $stats['events'], '📅', 'from-ink-700 to-ink-900'],
            ['Pending approvals', $stats['pending'], '⏳', 'from-amber-400 to-amber-600'],
            ['Certificates issued', $stats['certificates'], '🎖️', 'from-victory-400 to-victory-600'],
        ] as [$label, $value, $icon, $grad])
            <div class="card p-5">
                <div class="flex items-center justify-between">
                    <span class="grid h-11 w-11 place-items-center rounded-xl bg-gradient-to-br {{ $grad }} text-lg text-white">{{ $icon }}</span>
                    <span class="font-display text-3xl font-900 text-ink-900">{{ $value }}</span>
                </div>
                <p class="mt-3 text-sm font-semibold text-ink-500">{{ $label }}</p>
            </div>
        @endforeach
    </div>

    <div class="mt-6 grid gap-6 lg:grid-cols-3">
        {{-- Recent registrations --}}
        <div class="card lg:col-span-2">
            <div class="flex items-center justify-between border-b border-ink-100 px-5 py-4">
                <h2 class="font-display text-base font-800 text-ink-900">Recent registrations</h2>
                <a href="{{ route('admin.registrations.index') }}" class="text-sm font-semibold text-saffron-600">View all →</a>
            </div>
            <div class="divide-y divide-ink-100">
                @foreach ($recentRegistrations as $reg)
                    <div class="flex items-center gap-4 px-5 py-3">
                        <x-avatar :athlete="$reg->athlete" size="sm" />
                        <div class="min-w-0 flex-1">
                            <p class="truncate text-sm font-semibold text-ink-900">{{ $reg->athlete->name }}</p>
                            <p class="truncate text-xs text-ink-500">{{ $reg->event->name }} · {{ $reg->category }}</p>
                        </div>
                        <x-status-badge :status="$reg->status" />
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Registration status breakdown --}}
        <div class="card p-5">
            <h2 class="font-display text-base font-800 text-ink-900">Entry status</h2>
            @php $total = max(array_sum($regByStatus), 1); @endphp
            <div class="mt-5 space-y-4">
                @foreach (['approved' => 'bg-victory-500', 'pending' => 'bg-amber-500', 'rejected' => 'bg-rose-500'] as $key => $bar)
                    <div>
                        <div class="mb-1 flex items-center justify-between text-sm">
                            <span class="font-semibold capitalize text-ink-700">{{ $key }}</span>
                            <span class="text-ink-500">{{ $regByStatus[$key] }}</span>
                        </div>
                        <div class="h-2.5 w-full overflow-hidden rounded-full bg-ink-100">
                            <div class="{{ $bar }} h-full rounded-full" style="width: {{ round($regByStatus[$key] / $total * 100) }}%"></div>
                        </div>
                    </div>
                @endforeach
            </div>
            <a href="{{ route('admin.registrations.index', ['status' => 'pending']) }}" class="btn-primary btn-sm mt-6 w-full">Review {{ $regByStatus['pending'] }} pending</a>
        </div>
    </div>

    <div class="mt-6 grid gap-6 lg:grid-cols-3">
        {{-- Upcoming fixtures --}}
        <div class="card lg:col-span-2">
            <div class="flex items-center justify-between border-b border-ink-100 px-5 py-4">
                <h2 class="font-display text-base font-800 text-ink-900">Upcoming & live fixtures</h2>
                <a href="{{ route('admin.fixtures.index') }}" class="text-sm font-semibold text-saffron-600">Manage results →</a>
            </div>
            <div class="divide-y divide-ink-100">
                @forelse ($upcomingFixtures as $fx)
                    <div class="flex items-center gap-4 px-5 py-3 text-sm">
                        <span class="w-24 shrink-0 text-xs text-ink-400">{{ $fx->scheduled_at?->format('d M · H:i') }}</span>
                        <span class="flex-1 truncate font-semibold text-ink-800">{{ $fx->athleteA?->name ?? 'TBD' }} <span class="text-ink-400">vs</span> {{ $fx->athleteB?->name ?? 'TBD' }}</span>
                        <span class="hidden text-xs text-ink-400 sm:block">{{ $fx->round }}</span>
                        <x-status-badge :status="$fx->status" />
                    </div>
                @empty
                    <p class="px-5 py-6 text-sm text-ink-500">No scheduled fixtures.</p>
                @endforelse
            </div>
        </div>

        {{-- Medal leaders --}}
        <div class="card p-5">
            <h2 class="font-display text-base font-800 text-ink-900">Medal leaders</h2>
            <div class="mt-4 space-y-3">
                @foreach ($topStates as $i => $state)
                    <div class="flex items-center gap-3">
                        <span class="grid h-7 w-7 place-items-center rounded-full text-xs font-800 {{ $i === 0 ? 'bg-saffron-500 text-white' : 'bg-ink-100 text-ink-500' }}">{{ $i + 1 }}</span>
                        <span class="flex-1 text-sm font-semibold text-ink-800">{{ $state->state }}</span>
                        <span class="text-xs text-ink-500">🥇{{ $state->gold }} 🥈{{ $state->silver }} 🥉{{ $state->bronze }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection
