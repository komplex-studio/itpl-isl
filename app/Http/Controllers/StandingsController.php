<?php

namespace App\Http\Controllers;

use App\Models\MedalTally;

class StandingsController extends Controller
{
    public function index()
    {
        $tally = MedalTally::get()
            ->sortByDesc(fn ($t) => [$t->gold, $t->silver, $t->bronze])
            ->values();

        return view('public.standings', [
            'tally' => $tally,
            'totals' => [
                'gold' => $tally->sum('gold'),
                'silver' => $tally->sum('silver'),
                'bronze' => $tally->sum('bronze'),
            ],
        ]);
    }
}
