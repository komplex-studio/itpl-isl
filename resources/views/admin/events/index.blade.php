@extends('admin.layout')
@section('title', 'Events')
@section('heading', 'Events')

@section('content')
    <div class="card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-ink-50 text-xs uppercase tracking-wider text-ink-500">
                    <tr>
                        <th class="px-5 py-3">Event</th>
                        <th class="px-5 py-3">Dates</th>
                        <th class="px-5 py-3">Venue</th>
                        <th class="px-5 py-3 text-center">Entries</th>
                        <th class="px-5 py-3 text-center">Fixtures</th>
                        <th class="px-5 py-3">Status</th>
                        <th class="px-5 py-3 text-right">Reg.</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-ink-100">
                    @foreach ($events as $event)
                        <tr class="hover:bg-ink-50/60">
                            <td class="px-5 py-3">
                                <div class="flex items-center gap-3">
                                    <span class="grid h-10 w-10 place-items-center rounded-lg bg-ink-50 text-lg">{{ $event->sport->icon }}</span>
                                    <div>
                                        <p class="font-semibold text-ink-900">{{ $event->name }}</p>
                                        <p class="text-xs text-ink-400">{{ $event->sport->name }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-5 py-3 text-ink-600">{{ $event->date_range }}</td>
                            <td class="px-5 py-3 text-ink-600">{{ $event->city }}, {{ $event->state }}</td>
                            <td class="px-5 py-3 text-center font-semibold text-ink-800">{{ $event->registrations_count }}</td>
                            <td class="px-5 py-3 text-center font-semibold text-ink-800">{{ $event->fixtures_count }}</td>
                            <td class="px-5 py-3"><x-status-badge :status="$event->status" /></td>
                            <td class="px-5 py-3 text-right">
                                @if ($event->registration_open)
                                    <span class="chip-victory">Open</span>
                                @else
                                    <span class="chip-ink">Closed</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
