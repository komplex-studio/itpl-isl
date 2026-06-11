<form method="POST" action="{{ $action }}" class="card max-w-3xl space-y-5 p-6">
    @csrf
    @unless ($method === 'POST') @method($method) @endunless

    <div class="grid gap-5 sm:grid-cols-2">
        <div>
            <label class="label">Athlete</label>
            <select name="athlete_id" class="input" required>
                <option value="">Select athlete…</option>
                @foreach ($athletes as $athlete)
                    <option value="{{ $athlete->id }}" @selected((string) old('athlete_id', $registration->athlete_id) === (string) $athlete->id)>{{ $athlete->name }} · {{ $athlete->code }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="label">Event</label>
            <select name="event_id" class="input" required>
                <option value="">Select event…</option>
                @foreach ($events as $event)
                    <option value="{{ $event->id }}" @selected((string) old('event_id', $registration->event_id) === (string) $event->id)>{{ $event->sport->icon }} {{ $event->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="label">Category</label>
            <input name="category" value="{{ old('category', $registration->category) }}" class="input" placeholder="Men 57kg" required>
        </div>
        <div>
            <label class="label">Status</label>
            <select name="status" class="input">
                @foreach (['pending' => 'Pending', 'approved' => 'Approved', 'rejected' => 'Rejected'] as $value => $label)
                    <option value="{{ $value }}" @selected(old('status', $registration->status ?? 'pending') === $value)>{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="label">Submitted on</label>
            <input type="date" name="registered_at" value="{{ old('registered_at', optional($registration->registered_at)->format('Y-m-d')) }}" class="input">
        </div>
    </div>

    <div>
        <label class="label">Notes</label>
        <textarea name="notes" rows="3" class="input">{{ old('notes', $registration->notes) }}</textarea>
    </div>

    <div class="flex gap-3 pt-2">
        <button class="btn-primary">{{ $submit }}</button>
        <a href="{{ route('admin.registrations.index') }}" class="btn-ghost">Cancel</a>
    </div>
</form>
