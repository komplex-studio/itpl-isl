<?php

namespace App\Http\Controllers;

use App\Models\Sport;

class SportController extends Controller
{
    public function index()
    {
        return view('public.sports.index', [
            'sports' => Sport::withCount(['events'])->get(),
        ]);
    }

    public function show(Sport $sport)
    {
        $sport->load(['events' => fn ($q) => $q->orderBy('start_date')]);

        return view('public.sports.show', [
            'sport' => $sport,
        ]);
    }
}
