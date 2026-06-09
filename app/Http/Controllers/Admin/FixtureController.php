<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Fixture;
use Illuminate\Http\Request;

class FixtureController extends Controller
{
    public function index(Request $request)
    {
        $fixtures = Fixture::with(['event.sport', 'athleteA', 'athleteB', 'winner'])
            ->when($request->event, fn ($q, $e) => $q->where('event_id', $e))
            ->orderBy('scheduled_at')
            ->get();

        return view('admin.fixtures.index', [
            'fixtures' => $fixtures,
            'events' => Event::whereHas('fixtures')->orderBy('name')->get(),
            'filters' => $request->only('event'),
        ]);
    }

    public function update(Request $request, Fixture $fixture)
    {
        $data = $request->validate([
            'score_a' => ['nullable', 'string', 'max:10'],
            'score_b' => ['nullable', 'string', 'max:10'],
            'winner_id' => ['nullable', 'in:'.implode(',', array_filter([$fixture->athlete_a_id, $fixture->athlete_b_id]))],
        ]);

        $fixture->update([
            'score_a' => $data['score_a'] ?? null,
            'score_b' => $data['score_b'] ?? null,
            'winner_id' => $data['winner_id'] ?? null,
            'status' => ! empty($data['winner_id']) ? 'completed' : $fixture->status,
        ]);

        return back()->with('flash', "Result saved for {$fixture->round}.");
    }
}
