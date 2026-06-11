@extends('admin.layout')
@section('title', 'Athletes')
@section('heading', 'Athletes')

@section('content')
    <div class="mb-5 flex items-center gap-3">
        <form method="GET" class="flex-1">
            <input name="q" value="{{ $filters['q'] ?? '' }}" placeholder="Search by name, ID or state…" class="input max-w-md">
        </form>
        <a href="{{ route('admin.athletes.create') }}" class="btn-primary btn-sm shrink-0">+ New athlete</a>
    </div>

    <div class="card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-ink-50 text-xs uppercase tracking-wider text-ink-500">
                    <tr>
                        <th class="px-5 py-3">Athlete</th>
                        <th class="px-5 py-3">ISL ID</th>
                        <th class="px-5 py-3">State</th>
                        <th class="px-5 py-3">Gender</th>
                        <th class="px-5 py-3 text-center">Entries</th>
                        <th class="px-5 py-3">Contact</th>
                        <th class="px-5 py-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-ink-100">
                    @forelse ($athletes as $athlete)
                        <tr class="hover:bg-ink-50/60">
                            <td class="px-5 py-3">
                                <div class="flex items-center gap-3">
                                    <x-avatar :athlete="$athlete" size="sm" />
                                    <span class="font-semibold text-ink-900">{{ $athlete->name }}</span>
                                </div>
                            </td>
                            <td class="px-5 py-3 font-mono text-xs text-ink-500">{{ $athlete->code }}</td>
                            <td class="px-5 py-3 text-ink-600">{{ $athlete->state }}</td>
                            <td class="px-5 py-3 text-ink-600">{{ $athlete->gender === 'F' ? 'Female' : 'Male' }}</td>
                            <td class="px-5 py-3 text-center font-semibold text-ink-800">{{ $athlete->registrations_count }}</td>
                            <td class="px-5 py-3 text-xs text-ink-500">{{ $athlete->email }}</td>
                            <td class="px-5 py-3">
                                <div class="flex justify-end gap-1.5">
                                    <a href="{{ route('admin.athletes.edit', $athlete) }}" class="chip border border-ink-200 bg-white text-ink-700 hover:bg-ink-50">Edit</a>
                                    <x-admin.delete-button :action="route('admin.athletes.destroy', $athlete)" label="Delete {{ $athlete->name }}?" />
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="px-5 py-10 text-center text-ink-500">No athletes found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-5">{{ $athletes->links() }}</div>
@endsection
