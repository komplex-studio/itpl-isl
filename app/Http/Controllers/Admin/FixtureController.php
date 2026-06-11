<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Athlete;
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

    public function create()
    {
        return view('admin.fixtures.create', $this->formData(new Fixture(['round_order' => 1, 'slot' => 1, 'status' => 'scheduled'])));
    }

    public function store(Request $request)
    {
        Fixture::create($this->validateData($request));

        return redirect()->route('admin.fixtures.index')->with('flash', 'Fixture created.');
    }

    public function edit(Fixture $fixture)
    {
        return view('admin.fixtures.edit', $this->formData($fixture));
    }

    public function update(Request $request, Fixture $fixture)
    {
        $fixture->update($this->validateData($request));

        return redirect()->route('admin.fixtures.index')->with('flash', "Fixture “{$fixture->round}” updated.");
    }

    public function destroy(Fixture $fixture)
    {
        $fixture->delete();

        return redirect()->route('admin.fixtures.index')->with('flash', 'Fixture deleted.');
    }

    /** Inline result entry from the index list. */
    public function result(Request $request, Fixture $fixture)
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

    private function formData(Fixture $fixture): array
    {
        return [
            'fixture' => $fixture,
            'events' => Event::with('sport')->orderBy('name')->get(),
            'athletes' => Athlete::orderBy('name')->get(),
        ];
    }

    private function validateData(Request $request): array
    {
        $data = $request->validate([
            'event_id' => ['required', 'exists:events,id'],
            'round' => ['required', 'string', 'max:60'],
            'round_order' => ['required', 'integer', 'min:1', 'max:20'],
            'slot' => ['required', 'integer', 'min:1', 'max:64'],
            'athlete_a_id' => ['nullable', 'exists:athletes,id'],
            'athlete_b_id' => ['nullable', 'different:athlete_a_id', 'exists:athletes,id'],
            'scheduled_at' => ['nullable', 'date'],
            'venue' => ['nullable', 'string', 'max:160'],
            'status' => ['required', 'in:scheduled,live,completed'],
            'winner_id' => ['nullable', 'exists:athletes,id'],
            'score_a' => ['nullable', 'string', 'max:10'],
            'score_b' => ['nullable', 'string', 'max:10'],
        ]);

        // Winner must be one of the two competitors.
        if (! empty($data['winner_id']) && ! in_array($data['winner_id'], array_filter([$data['athlete_a_id'] ?? null, $data['athlete_b_id'] ?? null]))) {
            $data['winner_id'] = null;
        }

        return $data;
    }
}
