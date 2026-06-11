<form method="POST" action="{{ $action }}" class="card max-w-3xl space-y-5 p-6">
    @csrf
    @unless ($method === 'POST') @method($method) @endunless

    @if ($certificate->number)
        <div class="rounded-xl bg-ink-50 px-4 py-3 text-sm text-ink-600">
            Certificate no. <span class="font-mono font-semibold text-ink-900">{{ $certificate->number }}</span> <span class="text-ink-400">— assigned automatically.</span>
        </div>
    @endif

    <div class="grid gap-5 sm:grid-cols-2">
        <div>
            <label class="label">Athlete</label>
            <select name="athlete_id" class="input" required>
                <option value="">Select athlete…</option>
                @foreach ($athletes as $athlete)
                    <option value="{{ $athlete->id }}" @selected((string) old('athlete_id', $certificate->athlete_id) === (string) $athlete->id)>{{ $athlete->name }} · {{ $athlete->code }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="label">Event</label>
            <select name="event_id" class="input" required>
                <option value="">Select event…</option>
                @foreach ($events as $event)
                    <option value="{{ $event->id }}" @selected((string) old('event_id', $certificate->event_id) === (string) $event->id)>{{ $event->sport->icon }} {{ $event->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="label">Type</label>
            <select name="type" class="input">
                @foreach (['winner' => '🥇 Champion', 'runner_up' => '🥈 Runner-up', 'bronze' => '🥉 Bronze', 'participation' => '🎖️ Participation'] as $value => $label)
                    <option value="{{ $value }}" @selected(old('type', $certificate->type ?? 'participation') === $value)>{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="label">Issued on</label>
            <input type="date" name="issued_at" value="{{ old('issued_at', optional($certificate->issued_at)->format('Y-m-d')) }}" class="input" required>
        </div>
    </div>

    <div>
        <label class="label">Title</label>
        <input name="title" value="{{ old('title', $certificate->title) }}" class="input" placeholder="Gold Medal — 100m Sprint" required>
    </div>

    <div class="flex gap-3 pt-2">
        <button class="btn-primary">{{ $submit }}</button>
        <a href="{{ route('admin.certificates.index') }}" class="btn-ghost">Cancel</a>
    </div>
</form>
