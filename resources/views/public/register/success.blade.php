@extends('layouts.public')
@section('title', 'Registration Complete')

@section('content')
    <section class="container-x py-16">
        <div class="mx-auto max-w-2xl text-center">
            <span class="inline-grid h-16 w-16 place-items-center rounded-full bg-victory-500/15 text-3xl text-victory-600">✓</span>
            <h1 class="mt-5 font-display text-4xl font-900 text-ink-900">You're registered!</h1>
            <p class="mt-3 text-ink-500">Welcome to the Indian Sports League, {{ $athlete->name }}. Your entry is pending organiser approval.</p>
        </div>

        {{-- ID card --}}
        <div class="mx-auto mt-10 max-w-md">
            <div class="overflow-hidden rounded-3xl bg-ink-950 text-white shadow-xl">
                <div class="flex items-center justify-between bg-gradient-to-r from-saffron-500 to-saffron-700 px-6 py-4">
                    <span class="font-display text-sm font-800 tracking-wide">INDIAN SPORTS LEAGUE</span>
                    <span class="text-xs font-semibold text-saffron-100">SEASON 2026</span>
                </div>
                <div class="flex items-center gap-5 p-6">
                    <x-avatar :athlete="$athlete" size="xl" class="ring-4 ring-white/10" />
                    <div>
                        <p class="text-xs uppercase tracking-wider text-ink-400">Athlete</p>
                        <p class="font-display text-2xl font-800">{{ $athlete->name }}</p>
                        <p class="mt-1 text-sm text-ink-300">{{ $athlete->state }}@if($athlete->city), {{ $athlete->city }}@endif</p>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-px bg-white/10">
                    <div class="bg-ink-950 px-6 py-4">
                        <p class="text-xs uppercase tracking-wider text-ink-400">Unique ID</p>
                        <p class="font-display text-lg font-800 text-saffron-400">{{ $athlete->code }}</p>
                    </div>
                    <div class="bg-ink-950 px-6 py-4">
                        <p class="text-xs uppercase tracking-wider text-ink-400">Status</p>
                        <p class="mt-0.5"><x-status-badge :status="$registration->status" /></p>
                    </div>
                </div>
                @if ($registration)
                    <div class="border-t border-white/10 px-6 py-4 text-sm">
                        <p class="text-ink-400">Entered event</p>
                        <p class="font-semibold">{{ $registration->event->sport->icon }} {{ $registration->event->name }} · {{ $registration->category }}</p>
                    </div>
                @endif
            </div>

            <div class="mt-6 flex flex-col justify-center gap-3 sm:flex-row">
                <a href="{{ route('schedule') }}" class="btn-primary">Browse more events</a>
                <a href="{{ route('home') }}" class="btn-ghost">Back to home</a>
            </div>
            <p class="mt-4 text-center text-xs text-ink-400">Tip: save your ID <strong>{{ $athlete->code }}</strong> — you'll use it to verify certificates and enter future events.</p>
        </div>
    </section>
@endsection
