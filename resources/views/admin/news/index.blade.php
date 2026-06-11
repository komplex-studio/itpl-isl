@extends('admin.layout')
@section('title', 'News')
@section('heading', 'News & media')

@section('content')
    <div class="mb-5 flex justify-end">
        <a href="{{ route('admin.news.create') }}" class="btn-primary btn-sm">+ New article</a>
    </div>

    <div class="card overflow-hidden">
        <table class="w-full text-left text-sm">
            <thead class="bg-ink-50 text-xs uppercase tracking-wider text-ink-500">
                <tr>
                    <th class="px-5 py-3">Article</th>
                    <th class="px-5 py-3">Category</th>
                    <th class="px-5 py-3">Published</th>
                    <th class="px-5 py-3 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-ink-100">
                @foreach ($articles as $article)
                    <tr class="hover:bg-ink-50/60">
                        <td class="px-5 py-3">
                            <div class="flex items-center gap-3">
                                <span class="h-9 w-12 shrink-0 rounded-lg bg-gradient-to-br {{ $article->gradient }}"></span>
                                <span class="font-semibold text-ink-900">{{ $article->title }}</span>
                            </div>
                        </td>
                        <td class="px-5 py-3"><span class="chip-ink">{{ $article->category }}</span></td>
                        <td class="px-5 py-3 text-ink-500">{{ $article->published_at->format('d M Y') }}</td>
                        <td class="px-5 py-3">
                            <div class="flex justify-end gap-1.5">
                                <a href="{{ route('news.show', $article) }}" target="_blank" class="chip bg-white border border-ink-200 text-ink-700 hover:bg-ink-50">Open ↗</a>
                                <a href="{{ route('admin.news.edit', $article) }}" class="chip border border-ink-200 bg-white text-ink-700 hover:bg-ink-50">Edit</a>
                                <x-admin.delete-button :action="route('admin.news.destroy', $article)" label="Delete this article?" />
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
