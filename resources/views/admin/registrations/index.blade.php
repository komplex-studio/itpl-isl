@extends('admin.layout')
@section('title', 'Registrations')
@section('heading', 'Registrations')

@section('content')
    {{-- Filter tabs --}}
    <div class="mb-5 flex flex-wrap items-center gap-2">
        @php $current = $filters['status'] ?? 'all'; @endphp
        @foreach (['all' => 'All', 'pending' => 'Pending', 'approved' => 'Approved', 'rejected' => 'Rejected'] as $key => $label)
            <a href="{{ route('admin.registrations.index', array_merge($filters, ['status' => $key === 'all' ? null : $key])) }}"
               class="chip {{ $current === $key ? 'bg-ink-900 text-white' : 'bg-white text-ink-600 border border-ink-200' }}">
                {{ $label }} <span class="opacity-70">{{ $counts[$key] }}</span>
            </a>
        @endforeach

        <form method="GET" class="ml-auto">
            @if (!empty($filters['status'])) <input type="hidden" name="status" value="{{ $filters['status'] }}"> @endif
            <select name="event" class="input min-w-52 py-2 text-sm" onchange="this.form.submit()">
                <option value="">All events</option>
                @foreach ($events as $event)
                    <option value="{{ $event->id }}" @selected((string)($filters['event'] ?? '') === (string)$event->id)>{{ $event->name }}</option>
                @endforeach
            </select>
        </form>
    </div>

    <div class="card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-ink-50 text-xs uppercase tracking-wider text-ink-500">
                    <tr>
                        <th class="px-5 py-3">Athlete</th>
                        <th class="px-5 py-3">Event</th>
                        <th class="px-5 py-3">Category</th>
                        <th class="px-5 py-3">Submitted</th>
                        <th class="px-5 py-3">Status</th>
                        <th class="px-5 py-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-ink-100">
                    @forelse ($registrations as $reg)
                        <tr class="hover:bg-ink-50/60">
                            <td class="px-5 py-3">
                                <div class="flex items-center gap-3">
                                    <x-avatar :athlete="$reg->athlete" size="sm" />
                                    <div>
                                        <p class="font-semibold text-ink-900">{{ $reg->athlete->name }}</p>
                                        <p class="text-xs text-ink-400">{{ $reg->athlete->code }} · {{ $reg->athlete->state }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-5 py-3 text-ink-600">{{ $reg->event->sport->icon }} {{ $reg->event->name }}</td>
                            <td class="px-5 py-3 text-ink-600">{{ $reg->category }}</td>
                            <td class="px-5 py-3 text-ink-500">{{ $reg->registered_at?->format('d M Y') }}</td>
                            <td class="px-5 py-3"><x-status-badge :status="$reg->status" /></td>
                            <td class="px-5 py-3">
                                <div class="flex justify-end gap-1.5">
                                    @if ($reg->status !== 'approved')
                                        <form method="POST" action="{{ route('admin.registrations.update', $reg) }}">
                                            @csrf @method('PATCH')
                                            <input type="hidden" name="status" value="approved">
                                            <button class="chip bg-victory-500 text-white hover:bg-victory-600">Approve</button>
                                        </form>
                                    @endif
                                    @if ($reg->status !== 'rejected')
                                        <form method="POST" action="{{ route('admin.registrations.update', $reg) }}">
                                            @csrf @method('PATCH')
                                            <input type="hidden" name="status" value="rejected">
                                            <button class="chip bg-white text-rose-600 border border-rose-200 hover:bg-rose-50">Reject</button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="px-5 py-10 text-center text-ink-500">No registrations match these filters.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-5">{{ $registrations->links() }}</div>
@endsection
