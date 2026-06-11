<form method="POST" action="{{ $action }}" class="card max-w-3xl space-y-5 p-6">
    @csrf
    @unless ($method === 'POST') @method($method) @endunless

    <div>
        <label class="label">Title</label>
        <input name="title" value="{{ old('title', $article->title) }}" class="input" required>
    </div>

    <div class="grid gap-5 sm:grid-cols-2">
        <div>
            <label class="label">Category</label>
            <input name="category" value="{{ old('category', $article->category) }}" class="input" placeholder="Results" required>
        </div>
        <div>
            <label class="label">Published</label>
            <input type="date" name="published_at" value="{{ old('published_at', optional($article->published_at)->format('Y-m-d')) }}" class="input" required>
        </div>
    </div>

    <div>
        <label class="label">Photo URL <span class="font-normal text-ink-400">— optional</span></label>
        <input name="image" value="{{ old('image', $article->image) }}" class="input" placeholder="https://…">
    </div>
    <div>
        <label class="label">Gradient <span class="font-normal text-ink-400">— fallback tint</span></label>
        <input name="gradient" value="{{ old('gradient', $article->gradient) }}" class="input" placeholder="from-saffron-500 to-ink-800">
    </div>

    <div>
        <label class="label">Excerpt</label>
        <textarea name="excerpt" rows="2" class="input" required>{{ old('excerpt', $article->excerpt) }}</textarea>
    </div>
    <div>
        <label class="label">Body <span class="font-normal text-ink-400">— blank lines separate paragraphs</span></label>
        <textarea name="body" rows="8" class="input" required>{{ old('body', $article->body) }}</textarea>
    </div>

    <div class="flex gap-3 pt-2">
        <button class="btn-primary">{{ $submit }}</button>
        <a href="{{ route('admin.news.index') }}" class="btn-ghost">Cancel</a>
    </div>
</form>
