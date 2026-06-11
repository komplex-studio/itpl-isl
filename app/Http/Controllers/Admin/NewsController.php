<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class NewsController extends Controller
{
    public function index()
    {
        return view('admin.news.index', [
            'articles' => News::latest('published_at')->get(),
        ]);
    }

    public function create()
    {
        return view('admin.news.create', ['article' => new News(['published_at' => now()])]);
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);
        $data['slug'] = $this->uniqueSlug($data['title']);
        News::create($data);

        return redirect()->route('admin.news.index')->with('flash', "Article “{$data['title']}” published.");
    }

    public function edit(News $news)
    {
        return view('admin.news.edit', ['article' => $news]);
    }

    public function update(Request $request, News $news)
    {
        $data = $this->validateData($request, $news);
        $data['slug'] = $this->uniqueSlug($data['title'], $news);
        $news->update($data);

        return redirect()->route('admin.news.index')->with('flash', "Article “{$news->title}” updated.");
    }

    public function destroy(News $news)
    {
        $news->delete();

        return redirect()->route('admin.news.index')->with('flash', 'Article deleted.');
    }

    private function validateData(Request $request, ?News $news = null): array
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:200'],
            'category' => ['required', 'string', 'max:60'],
            'published_at' => ['required', 'date'],
            'image' => ['nullable', 'url', 'max:500'],
            'gradient' => ['nullable', 'string', 'max:120'],
            'excerpt' => ['required', 'string', 'max:500'],
            'body' => ['required', 'string'],
        ]);

        $data['gradient'] = ($data['gradient'] ?? null) ?: 'from-ink-800 to-ink-950';

        return $data;
    }

    private function uniqueSlug(string $title, ?News $news = null): string
    {
        $base = Str::slug($title);
        $slug = $base;
        $i = 2;
        while (News::where('slug', $slug)->when($news, fn ($q) => $q->whereKeyNot($news->getKey()))->exists()) {
            $slug = $base.'-'.$i++;
        }

        return $slug;
    }
}
