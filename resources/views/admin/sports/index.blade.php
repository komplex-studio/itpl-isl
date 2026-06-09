@extends('admin.layout')
@section('title', 'Sports')
@section('heading', 'Sports')

@section('content')
    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
        @foreach ($sports as $sport)
            <div class="card p-5">
                <div class="flex items-center justify-between">
                    <span class="text-3xl">{{ $sport->icon }}</span>
                    <span class="chip-ink capitalize">{{ $sport->format }}</span>
                </div>
                <h2 class="mt-4 font-display text-lg font-800 text-ink-900">{{ $sport->name }}</h2>
                <p class="mt-1 text-sm text-ink-500">{{ $sport->tagline }}</p>
                <p class="mt-4 text-sm font-semibold text-ink-700">{{ $sport->events_count }} {{ Str::plural('event', $sport->events_count) }}</p>
            </div>
        @endforeach
    </div>
@endsection
