<form method="POST" action="{{ $action }}" class="card max-w-xl space-y-5 p-6">
    @csrf
    @unless ($method === 'POST') @method($method) @endunless

    <div>
        <label class="label">State / UT</label>
        <input name="state" value="{{ old('state', $tally->state) }}" class="input" required>
    </div>

    <div class="grid grid-cols-3 gap-4">
        <div>
            <label class="label">🥇 Gold</label>
            <input type="number" name="gold" value="{{ old('gold', $tally->gold ?? 0) }}" class="input" min="0" required>
        </div>
        <div>
            <label class="label">🥈 Silver</label>
            <input type="number" name="silver" value="{{ old('silver', $tally->silver ?? 0) }}" class="input" min="0" required>
        </div>
        <div>
            <label class="label">🥉 Bronze</label>
            <input type="number" name="bronze" value="{{ old('bronze', $tally->bronze ?? 0) }}" class="input" min="0" required>
        </div>
    </div>

    <div class="flex gap-3 pt-2">
        <button class="btn-primary">{{ $submit }}</button>
        <a href="{{ route('admin.medal-tallies.index') }}" class="btn-ghost">Cancel</a>
    </div>
</form>
