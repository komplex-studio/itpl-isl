@extends('layouts.public')
@section('title', $article->title)

@section('content')
    <section class="relative overflow-hidden bg-gradient-to-br {{ $article->gradient }} text-white">
        <div class="container-x relative py-16">
            <a href="{{ route('news.index') }}" class="text-sm text-white/80 hover:text-white">← All news</a>
            <span class="chip mt-5 bg-white/20 text-white backdrop-blur">{{ $article->category }}</span>
            <h1 class="mt-4 max-w-3xl font-display text-4xl font-900 leading-tight sm:text-5xl">{{ $article->title }}</h1>
            <p class="mt-4 text-sm text-white/80">{{ $article->published_at->format('d M Y') }}</p>
        </div>
    </section>

    <article class="container-x grid gap-10 py-14 lg:grid-cols-3">
        <div class="lg:col-span-2">
            <p class="text-lg font-medium leading-relaxed text-ink-700">{{ $article->excerpt }}</p>
            <div class="mt-6 space-y-4 leading-relaxed text-ink-600">
                @foreach (preg_split('/\n\n+/', $article->body) as $para)
                    <p>{{ $para }}</p>
                @endforeach
            </div>
        </div>

        <aside>
            <h2 class="font-display text-lg font-800 text-ink-900">More stories</h2>
            <div class="mt-4 space-y-4">
                @foreach ($more as $item)
                    <a href="{{ route('news.show', $item) }}" class="group flex gap-3">
                        <span class="h-16 w-20 shrink-0 rounded-xl bg-gradient-to-br {{ $item->gradient }}"></span>
                        <div>
                            <p class="text-xs text-ink-400">{{ $item->published_at->format('d M Y') }}</p>
                            <p class="text-sm font-semibold leading-snug text-ink-800 group-hover:text-saffron-600">{{ $item->title }}</p>
                        </div>
                    </a>
                @endforeach
            </div>
        </aside>
    </article>
@endsection
