<?php

namespace App\Http\Controllers;

use App\Models\Athlete;
use App\Models\Event;
use App\Models\Fixture;
use App\Models\MedalTally;
use App\Models\News;
use App\Models\Registration;
use App\Models\Sport;

class HomeController extends Controller
{
    public function index()
    {
        $featured = Event::with('sport')
            ->orderByRaw("status = 'ongoing' desc")
            ->orderBy('start_date')
            ->first();

        $upcoming = Event::with('sport')
            ->whereIn('status', ['ongoing', 'upcoming'])
            ->orderBy('start_date')
            ->take(4)
            ->get();

        $latestResults = Fixture::with(['event.sport', 'athleteA', 'athleteB', 'winner'])
            ->where('status', 'completed')
            ->latest('scheduled_at')
            ->take(4)
            ->get();

        return view('public.home', [
            'featured' => $featured,
            'sports' => Sport::withCount('events')->get(),
            'upcoming' => $upcoming,
            'latestResults' => $latestResults,
            'topStates' => MedalTally::orderByDesc('gold')->orderByDesc('silver')->take(5)->get(),
            'news' => News::latest('published_at')->take(3)->get(),
            'stats' => [
                'athletes' => Athlete::count(),
                'events' => Event::count(),
                'sports' => Sport::count(),
                'registrations' => Registration::count(),
            ],
        ]);
    }
}
