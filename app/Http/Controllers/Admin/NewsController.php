<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\News;

class NewsController extends Controller
{
    public function index()
    {
        return view('admin.news.index', [
            'articles' => News::latest('published_at')->get(),
        ]);
    }
}
