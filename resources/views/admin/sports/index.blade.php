@extends('admin.layout')
@section('title', 'Sports')
@section('heading', 'Sports')

@section('content')
    <div class="mb-5 flex justify-end">
        <a href="{{ route('admin.sports.create') }}" class="btn-primary btn-sm">+ New sport</a>
    </div>

    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
        @foreach ($sports as $sport)
            <div class="card flex flex-col p-5">
                <div class="flex items-center justify-between">
                    <span class="text-3xl">{{ $sport->icon }}</span>
                    <span class="chip-ink capitalize">{{ $sport->format }}</span>
                </div>
                <h2 class="mt-4 font-display text-lg font-800 text-ink-900">{{ $sport->name }}</h2>
                <p class="mt-1 text-sm text-ink-500">{{ $sport->tagline }}</p>
                <p class="mt-4 flex-1 text-sm font-semibold text-ink-700">{{ $sport->events_count }} {{ Str::plural('event', $sport->events_count) }}</p>
                <div class="mt-4 flex gap-1.5 border-t border-ink-100 pt-4">
                    <a href="{{ route('admin.sports.edit', $sport) }}" class="chip border border-ink-200 bg-white text-ink-700 hover:bg-ink-50">Edit</a>
                    <x-admin.delete-button :action="route('admin.sports.destroy', $sport)" label="Delete {{ $sport->name }}?" />
                </div>
            </div>
        @endforeach
    </div>
@endsection
