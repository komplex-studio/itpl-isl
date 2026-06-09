@extends('layouts.public')
@section('title', 'Sports')

@section('content')
    <x-page-header
        eyebrow="Disciplines"
        title="Eight sports. One national league."
        subtitle="From the boxing ring to the kabaddi mat — explore every discipline in the Indian Sports League 2026 season." />

    <section class="container-x py-16">
        <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
            @foreach ($sports as $sport)
                <a href="{{ route('sports.show', $sport) }}" class="card group flex flex-col p-7 transition hover:-translate-y-1 hover:shadow-lg">
                    <div class="flex items-start justify-between">
                        <span class="text-5xl">{{ $sport->icon }}</span>
                        <span class="chip-ink capitalize">{{ $sport->format }}</span>
                    </div>
                    <h2 class="mt-5 font-display text-2xl font-800 text-ink-900 group-hover:text-saffron-600">{{ $sport->name }}</h2>
                    <p class="mt-1 text-sm font-semibold text-saffron-600">{{ $sport->tagline }}</p>
                    <p class="mt-3 flex-1 text-sm leading-relaxed text-ink-500">{{ $sport->description }}</p>
                    <p class="mt-5 text-sm font-semibold text-ink-700">
                        {{ $sport->events_count }} {{ Str::plural('event', $sport->events_count) }} this season →
                    </p>
                </a>
            @endforeach
        </div>
    </section>
@endsection
