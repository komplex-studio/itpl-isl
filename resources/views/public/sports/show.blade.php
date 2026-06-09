@extends('layouts.public')
@section('title', $sport->name)

@section('content')
    <x-page-header :eyebrow="ucfirst($sport->format).' format'" :title="$sport->name" :subtitle="$sport->description">
        <a href="{{ route('register.create') }}" class="btn-primary btn-sm">Register for {{ $sport->name }}</a>
    </x-page-header>

    <section class="container-x py-16">
        <div class="flex items-center gap-4">
            <span class="text-5xl">{{ $sport->icon }}</span>
            <div>
                <h2 class="font-display text-2xl font-800 text-ink-900">Events in {{ $sport->name }}</h2>
                <p class="text-sm text-ink-500">{{ $sport->events->count() }} scheduled this season</p>
            </div>
        </div>

        <div class="mt-8 grid gap-5 md:grid-cols-2">
            @forelse ($sport->events as $event)
                <a href="{{ route('events.show', $event) }}" class="card group overflow-hidden transition hover:-translate-y-1 hover:shadow-lg">
                    <div class="bg-gradient-to-br {{ $event->gradient }} p-6 text-white">
                        <div class="flex items-center justify-between">
                            <span class="text-3xl">{{ $sport->icon }}</span>
                            <x-status-badge :status="$event->status" />
                        </div>
                        <h3 class="mt-4 font-display text-xl font-800">{{ $event->name }}</h3>
                        <p class="mt-1 text-sm text-white/80">{{ $event->city }}, {{ $event->state }}</p>
                    </div>
                    <div class="flex items-center justify-between p-5 text-sm">
                        <span class="text-ink-500">{{ $event->date_range }}</span>
                        <span class="font-semibold text-saffron-600 group-hover:text-saffron-700">View event →</span>
                    </div>
                </a>
            @empty
                <p class="text-ink-500">No events scheduled yet for this sport.</p>
            @endforelse
        </div>
    </section>
@endsection
