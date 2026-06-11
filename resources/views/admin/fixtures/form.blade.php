<form method="POST" action="{{ $action }}" class="card max-w-3xl space-y-5 p-6">
    @csrf
    @unless ($method === 'POST') @method($method) @endunless

    <div class="grid gap-5 sm:grid-cols-2">
        <div class="sm:col-span-2">
            <label class="label">Event</label>
            <select name="event_id" class="input" required>
                <option value="">Select event…</option>
                @foreach ($events as $event)
                    <option value="{{ $event->id }}" @selected((string) old('event_id', $fixture->event_id) === (string) $event->id)>{{ $event->sport->icon }} {{ $event->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="label">Round</label>
            <input name="round" value="{{ old('round', $fixture->round) }}" class="input" placeholder="Quarter-final" required>
        </div>
        <div>
            <label class="label">Status</label>
            <select name="status" class="input">
                @foreach (['scheduled' => 'Scheduled', 'live' => 'Live', 'completed' => 'Completed'] as $value => $label)
                    <option value="{{ $value }}" @selected(old('status', $fixture->status ?? 'scheduled') === $value)>{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="label">Round order <span class="font-normal text-ink-400">— 1 = earliest</span></label>
            <input type="number" name="round_order" value="{{ old('round_order', $fixture->round_order ?? 1) }}" class="input" min="1" required>
        </div>
        <div>
            <label class="label">Slot</label>
            <input type="number" name="slot" value="{{ old('slot', $fixture->slot ?? 1) }}" class="input" min="1" required>
        </div>
        <div>
            <label class="label">Athlete A</label>
            <select name="athlete_a_id" class="input">
                <option value="">TBD</option>
                @foreach ($athletes as $athlete)
                    <option value="{{ $athlete->id }}" @selected((string) old('athlete_a_id', $fixture->athlete_a_id) === (string) $athlete->id)>{{ $athlete->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="label">Athlete B</label>
            <select name="athlete_b_id" class="input">
                <option value="">TBD</option>
                @foreach ($athletes as $athlete)
                    <option value="{{ $athlete->id }}" @selected((string) old('athlete_b_id', $fixture->athlete_b_id) === (string) $athlete->id)>{{ $athlete->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="label">Scheduled at</label>
            <input type="datetime-local" name="scheduled_at" value="{{ old('scheduled_at', optional($fixture->scheduled_at)->format('Y-m-d\TH:i')) }}" class="input">
        </div>
        <div>
            <label class="label">Venue</label>
            <input name="venue" value="{{ old('venue', $fixture->venue) }}" class="input">
        </div>
        <div>
            <label class="label">Score A</label>
            <input name="score_a" value="{{ old('score_a', $fixture->score_a) }}" class="input" maxlength="10">
        </div>
        <div>
            <label class="label">Score B</label>
            <input name="score_b" value="{{ old('score_b', $fixture->score_b) }}" class="input" maxlength="10">
        </div>
        <div class="sm:col-span-2">
            <label class="label">Winner <span class="font-normal text-ink-400">— must be Athlete A or B; ignored otherwise</span></label>
            <select name="winner_id" class="input">
                <option value="">No winner yet</option>
                @foreach ($athletes as $athlete)
                    <option value="{{ $athlete->id }}" @selected((string) old('winner_id', $fixture->winner_id) === (string) $athlete->id)>{{ $athlete->name }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="flex gap-3 pt-2">
        <button class="btn-primary">{{ $submit }}</button>
        <a href="{{ route('admin.fixtures.index') }}" class="btn-ghost">Cancel</a>
    </div>
</form>
