@extends('admin.layout')
@section('title', 'Certificates')
@section('heading', 'Certificates')

@section('content')
    <div class="mb-5 flex justify-end">
        <a href="{{ route('admin.certificates.create') }}" class="btn-primary btn-sm">+ Issue certificate</a>
    </div>

    <div class="card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-ink-50 text-xs uppercase tracking-wider text-ink-500">
                    <tr>
                        <th class="px-5 py-3">Number</th>
                        <th class="px-5 py-3">Athlete</th>
                        <th class="px-5 py-3">Event</th>
                        <th class="px-5 py-3">Type</th>
                        <th class="px-5 py-3">Issued</th>
                        <th class="px-5 py-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-ink-100">
                    @foreach ($certificates as $cert)
                        @php $typeLabel = ['winner'=>'🥇 Champion','runner_up'=>'🥈 Runner-up','bronze'=>'🥉 Bronze','participation'=>'🎖️ Participant'][$cert->type] ?? $cert->type; @endphp
                        <tr class="hover:bg-ink-50/60">
                            <td class="px-5 py-3 font-mono text-xs text-ink-600">{{ $cert->number }}</td>
                            <td class="px-5 py-3">
                                <div class="flex items-center gap-3">
                                    <x-avatar :athlete="$cert->athlete" size="sm" />
                                    <span class="font-semibold text-ink-900">{{ $cert->athlete->name }}</span>
                                </div>
                            </td>
                            <td class="px-5 py-3 text-ink-600">{{ $cert->event->sport->icon }} {{ $cert->event->name }}</td>
                            <td class="px-5 py-3 text-ink-600">{{ $typeLabel }}</td>
                            <td class="px-5 py-3 text-ink-500">{{ $cert->issued_at->format('d M Y') }}</td>
                            <td class="px-5 py-3">
                                <div class="flex justify-end gap-1.5">
                                    <a href="{{ route('certificates.show', $cert) }}" target="_blank" class="chip bg-white border border-ink-200 text-ink-700 hover:bg-ink-50">Open ↗</a>
                                    <a href="{{ route('admin.certificates.edit', $cert) }}" class="chip border border-ink-200 bg-white text-ink-700 hover:bg-ink-50">Edit</a>
                                    <x-admin.delete-button :action="route('admin.certificates.destroy', $cert)" label="Delete {{ $cert->number }}?" />
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-5">{{ $certificates->links() }}</div>
@endsection
