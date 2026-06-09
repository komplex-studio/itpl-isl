@extends('layouts.public')
@section('title', 'News & Media')

@section('content')
    <x-page-header eyebrow="Newsroom" title="News & media" subtitle="The latest from across the Indian Sports League." />

    <section class="container-x py-14">
        @php $lead = $articles->first(); $rest = $articles->skip(1); @endphp

        @if ($lead)
            <a href="{{ route('news.show', $lead) }}" class="card group grid overflow-hidden md:grid-cols-2">
                <div class="bg-gradient-to-br {{ $lead->gradient }} p-10 md:min-h-72">
                    <span class="chip bg-white/20 text-white backdrop-blur">{{ $lead->category }}</span>
                </div>
                <div class="flex flex-col justify-center p-8">
                    <p class="text-xs text-ink-400">{{ $lead->published_at->format('d M Y') }}</p>
                    <h2 class="mt-2 font-display text-2xl font-800 text-ink-900 group-hover:text-saffron-600">{{ $lead->title }}</h2>
                    <p class="mt-3 text-ink-500">{{ $lead->excerpt }}</p>
                    <span class="mt-5 text-sm font-semibold text-saffron-600">Read story →</span>
                </div>
            </a>
        @endif

        <div class="mt-8 grid gap-6 md:grid-cols-3">
            @foreach ($rest as $article)
                <a href="{{ route('news.show', $article) }}" class="card group overflow-hidden transition hover:-translate-y-1 hover:shadow-lg">
                    <div class="aspect-[16/9] bg-gradient-to-br {{ $article->gradient }} p-5">
                        <span class="chip bg-white/20 text-white backdrop-blur">{{ $article->category }}</span>
                    </div>
                    <div class="p-5">
                        <p class="text-xs text-ink-400">{{ $article->published_at->format('d M Y') }}</p>
                        <h3 class="mt-2 font-display text-lg font-700 leading-snug text-ink-900 group-hover:text-saffron-600">{{ $article->title }}</h3>
                        <p class="mt-2 line-clamp-2 text-sm text-ink-500">{{ $article->excerpt }}</p>
                    </div>
                </a>
            @endforeach
        </div>
    </section>
@endsection
