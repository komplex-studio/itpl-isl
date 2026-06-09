<?php

namespace App\Http\Controllers;

use App\Models\News;

class NewsController extends Controller
{
    public function index()
    {
        return view('public.news.index', [
            'articles' => News::latest('published_at')->get(),
        ]);
    }

    public function show(News $news)
    {
        return view('public.news.show', [
            'article' => $news,
            'more' => News::whereKeyNot($news->id)->latest('published_at')->take(3)->get(),
        ]);
    }
}
