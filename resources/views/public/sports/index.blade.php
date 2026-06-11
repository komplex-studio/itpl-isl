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
                <a href="{{ route('sports.show', $sport) }}" class="card group flex flex-col overflow-hidden transition hover:-translate-y-1 hover:shadow-lg">
                    <div class="relative h-48 bg-cover bg-center bg-gradient-to-br from-ink-700 to-ink-950"
                         @if ($sport->image) style="background-image:url('{{ $sport->image }}');" @endif>
                        <div class="absolute inset-0 bg-gradient-to-t from-ink-950/80 via-ink-950/10 to-transparent transition duration-500 group-hover:scale-105"></div>
                        <span class="absolute right-4 top-4 chip bg-white/20 capitalize text-white backdrop-blur">{{ $sport->format }}</span>
                        <h2 class="absolute inset-x-0 bottom-0 p-5 font-display text-2xl font-800 text-white">{{ $sport->icon }} {{ $sport->name }}</h2>
                    </div>
                    <div class="flex flex-1 flex-col p-7 pt-5">
                        <p class="text-sm font-semibold text-saffron-600">{{ $sport->tagline }}</p>
                        <p class="mt-3 flex-1 text-sm leading-relaxed text-ink-500">{{ $sport->description }}</p>
                        <p class="mt-5 text-sm font-semibold text-ink-700">
                            {{ $sport->events_count }} {{ Str::plural('event', $sport->events_count) }} this season →
                        </p>
                    </div>
                </a>
            @endforeach
        </div>
    </section>
@endsection
