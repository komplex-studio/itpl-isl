@extends('admin.layout')
@section('title', 'Medal tally')
@section('heading', 'Medal tally')

@section('content')
    <div class="mb-5 flex justify-end">
        <a href="{{ route('admin.medal-tallies.create') }}" class="btn-primary btn-sm">+ New state row</a>
    </div>

    <div class="card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-ink-50 text-xs uppercase tracking-wider text-ink-500">
                    <tr>
                        <th class="px-5 py-3">Rank</th>
                        <th class="px-5 py-3">State</th>
                        <th class="px-5 py-3 text-center">🥇</th>
                        <th class="px-5 py-3 text-center">🥈</th>
                        <th class="px-5 py-3 text-center">🥉</th>
                        <th class="px-5 py-3 text-center">Total</th>
                        <th class="px-5 py-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-ink-100">
                    @forelse ($tallies as $i => $tally)
                        <tr class="hover:bg-ink-50/60">
                            <td class="px-5 py-3 font-display font-800 text-ink-400">{{ $i + 1 }}</td>
                            <td class="px-5 py-3 font-semibold text-ink-900">{{ $tally->state }}</td>
                            <td class="px-5 py-3 text-center">{{ $tally->gold }}</td>
                            <td class="px-5 py-3 text-center">{{ $tally->silver }}</td>
                            <td class="px-5 py-3 text-center">{{ $tally->bronze }}</td>
                            <td class="px-5 py-3 text-center font-display font-800 text-saffron-600">{{ $tally->total }}</td>
                            <td class="px-5 py-3">
                                <div class="flex justify-end gap-1.5">
                                    <a href="{{ route('admin.medal-tallies.edit', $tally) }}" class="chip bg-white border border-ink-200 text-ink-700 hover:bg-ink-50">Edit</a>
                                    <x-admin.delete-button :action="route('admin.medal-tallies.destroy', $tally)" label="Delete {{ $tally->state }} row?" />
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="px-5 py-10 text-center text-ink-500">No states yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
