<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Athlete;
use App\Models\Certificate;
use App\Models\Event;
use App\Models\Fixture;
use App\Models\MedalTally;
use App\Models\Registration;
use App\Models\Sport;

class DashboardController extends Controller
{
    public function index()
    {
        return view('admin.dashboard', [
            'stats' => [
                'athletes' => Athlete::count(),
                'events' => Event::count(),
                'pending' => Registration::where('status', 'pending')->count(),
                'certificates' => Certificate::count(),
            ],
            'regByStatus' => [
                'approved' => Registration::where('status', 'approved')->count(),
                'pending' => Registration::where('status', 'pending')->count(),
                'rejected' => Registration::where('status', 'rejected')->count(),
            ],
            'recentRegistrations' => Registration::with(['athlete', 'event'])->latest('registered_at')->take(6)->get(),
            'upcomingFixtures' => Fixture::with(['event', 'athleteA', 'athleteB'])
                ->whereIn('status', ['scheduled', 'live'])
                ->orderBy('scheduled_at')
                ->take(5)
                ->get(),
            'sports' => Sport::withCount('events')->get(),
            'topStates' => MedalTally::get()->sortByDesc(fn ($t) => [$t->gold, $t->silver])->take(5)->values(),
        ]);
    }
}
