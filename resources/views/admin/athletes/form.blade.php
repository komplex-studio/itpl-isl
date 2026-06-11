<form method="POST" action="{{ $action }}" class="card max-w-3xl space-y-5 p-6">
    @csrf
    @unless ($method === 'POST') @method($method) @endunless

    @if ($athlete->code)
        <div class="rounded-xl bg-ink-50 px-4 py-3 text-sm text-ink-600">
            ISL ID <span class="font-mono font-semibold text-ink-900">{{ $athlete->code }}</span> <span class="text-ink-400">— assigned automatically, not editable.</span>
        </div>
    @endif

    <div class="grid gap-5 sm:grid-cols-2">
        <div class="sm:col-span-2">
            <label class="label">Full name</label>
            <input name="name" value="{{ old('name', $athlete->name) }}" class="input" required>
        </div>
        <div>
            <label class="label">Gender</label>
            <select name="gender" class="input">
                @foreach (['M' => 'Male', 'F' => 'Female'] as $value => $label)
                    <option value="{{ $value }}" @selected(old('gender', $athlete->gender) === $value)>{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="label">Date of birth</label>
            <input type="date" name="dob" value="{{ old('dob', optional($athlete->dob)->format('Y-m-d')) }}" class="input" required>
        </div>
        <div>
            <label class="label">State</label>
            <input name="state" value="{{ old('state', $athlete->state) }}" class="input" required>
        </div>
        <div>
            <label class="label">City</label>
            <input name="city" value="{{ old('city', $athlete->city) }}" class="input" required>
        </div>
        <div>
            <label class="label">Email</label>
            <input type="email" name="email" value="{{ old('email', $athlete->email) }}" class="input" required>
        </div>
        <div>
            <label class="label">Phone</label>
            <input name="phone" value="{{ old('phone', $athlete->phone) }}" class="input" placeholder="+91 9…">
        </div>
        <div class="sm:col-span-2">
            <label class="label">Avatar tint</label>
            <select name="avatar_tint" class="input">
                @foreach ($tints as $tint)
                    <option value="{{ $tint }}" @selected(old('avatar_tint', $athlete->avatar_tint) === $tint)>{{ $tint }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div>
        <label class="label">Bio</label>
        <textarea name="bio" rows="3" class="input">{{ old('bio', $athlete->bio) }}</textarea>
    </div>

    <div class="flex gap-3 pt-2">
        <button class="btn-primary">{{ $submit }}</button>
        <a href="{{ route('admin.athletes.index') }}" class="btn-ghost">Cancel</a>
    </div>
</form>
