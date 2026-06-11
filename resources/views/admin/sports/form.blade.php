<form method="POST" action="{{ $action }}" class="card max-w-3xl space-y-5 p-6">
    @csrf
    @unless ($method === 'POST') @method($method) @endunless

    <div class="grid gap-5 sm:grid-cols-2">
        <div>
            <label class="label">Name</label>
            <input name="name" value="{{ old('name', $sport->name) }}" class="input" required>
        </div>
        <div>
            <label class="label">Icon (emoji)</label>
            <input name="icon" value="{{ old('icon', $sport->icon) }}" class="input" placeholder="🥊" maxlength="16" required>
        </div>
        <div>
            <label class="label">Brand colour</label>
            <select name="color" class="input">
                @foreach (['saffron' => 'Saffron', 'ink' => 'Ink (navy)', 'victory' => 'Victory (green)'] as $value => $label)
                    <option value="{{ $value }}" @selected(old('color', $sport->color ?? 'saffron') === $value)>{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="label">Format</label>
            <select name="format" class="input">
                @foreach (['knockout' => 'Knockout', 'league' => 'League'] as $value => $label)
                    <option value="{{ $value }}" @selected(old('format', $sport->format ?? 'knockout') === $value)>{{ $label }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div>
        <label class="label">Tagline</label>
        <input name="tagline" value="{{ old('tagline', $sport->tagline) }}" class="input" placeholder="Punch above your weight">
    </div>

    <div>
        <label class="label">Photo URL <span class="font-normal text-ink-400">— falls back to a gradient if empty</span></label>
        <input name="image" value="{{ old('image', $sport->image) }}" class="input" placeholder="https://…">
    </div>

    <div>
        <label class="label">Description</label>
        <textarea name="description" rows="4" class="input">{{ old('description', $sport->description) }}</textarea>
    </div>

    <div class="flex gap-3 pt-2">
        <button class="btn-primary">{{ $submit }}</button>
        <a href="{{ route('admin.sports.index') }}" class="btn-ghost">Cancel</a>
    </div>
</form>
