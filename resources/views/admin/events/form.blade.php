<form method="POST" action="{{ $action }}" class="card max-w-3xl space-y-5 p-6">
    @csrf
    @unless ($method === 'POST') @method($method) @endunless

    <div class="grid gap-5 sm:grid-cols-2">
        <div class="sm:col-span-2">
            <label class="label">Event name</label>
            <input name="name" value="{{ old('name', $event->name) }}" class="input" required>
        </div>
        <div>
            <label class="label">Sport</label>
            <select name="sport_id" class="input" required>
                <option value="">Select sport…</option>
                @foreach ($sports as $sport)
                    <option value="{{ $sport->id }}" @selected((string) old('sport_id', $event->sport_id) === (string) $sport->id)>{{ $sport->icon }} {{ $sport->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="label">Season</label>
            <input name="season" value="{{ old('season', $event->season ?? '2026') }}" class="input" required>
        </div>
        <div>
            <label class="label">City</label>
            <input name="city" value="{{ old('city', $event->city) }}" class="input" required>
        </div>
        <div>
            <label class="label">State</label>
            <input name="state" value="{{ old('state', $event->state) }}" class="input" required>
        </div>
        <div class="sm:col-span-2">
            <label class="label">Venue</label>
            <input name="venue" value="{{ old('venue', $event->venue) }}" class="input" required>
        </div>
        <div>
            <label class="label">Start date</label>
            <input type="date" name="start_date" value="{{ old('start_date', optional($event->start_date)->format('Y-m-d')) }}" class="input" required>
        </div>
        <div>
            <label class="label">End date</label>
            <input type="date" name="end_date" value="{{ old('end_date', optional($event->end_date)->format('Y-m-d')) }}" class="input" required>
        </div>
        <div>
            <label class="label">Status</label>
            <select name="status" class="input">
                @foreach (['upcoming' => 'Upcoming', 'ongoing' => 'Ongoing', 'completed' => 'Completed'] as $value => $label)
                    <option value="{{ $value }}" @selected(old('status', $event->status ?? 'upcoming') === $value)>{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="label">Prize pool (₹)</label>
            <input type="number" name="prize_pool" value="{{ old('prize_pool', $event->prize_pool ?? 0) }}" class="input" min="0" required>
        </div>
    </div>

    <label class="flex items-center gap-2.5 text-sm font-semibold text-ink-700">
        <input type="checkbox" name="registration_open" value="1" class="h-4 w-4 rounded border-ink-300 text-saffron-500 focus:ring-saffron-400"
               @checked(old('registration_open', $event->registration_open)) >
        Registration open
    </label>

    <div>
        <label class="label">Photo URL <span class="font-normal text-ink-400">— optional</span></label>
        <input name="image" value="{{ old('image', $event->image) }}" class="input" placeholder="https://…">
    </div>
    <div>
        <label class="label">Gradient <span class="font-normal text-ink-400">— Tailwind classes used as fallback tint</span></label>
        <input name="gradient" value="{{ old('gradient', $event->gradient) }}" class="input" placeholder="from-saffron-600 to-ink-900">
    </div>
    <div>
        <label class="label">Summary</label>
        <textarea name="summary" rows="4" class="input">{{ old('summary', $event->summary) }}</textarea>
    </div>

    <div class="flex gap-3 pt-2">
        <button class="btn-primary">{{ $submit }}</button>
        <a href="{{ route('admin.events.index') }}" class="btn-ghost">Cancel</a>
    </div>
</form>
