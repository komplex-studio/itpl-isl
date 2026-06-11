<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MedalTally;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class MedalTallyController extends Controller
{
    public function index()
    {
        return view('admin.medal-tallies.index', [
            'tallies' => MedalTally::get()->sortByDesc(fn ($t) => [$t->gold, $t->silver, $t->bronze])->values(),
        ]);
    }

    public function create()
    {
        return view('admin.medal-tallies.create', ['tally' => new MedalTally(['gold' => 0, 'silver' => 0, 'bronze' => 0])]);
    }

    public function store(Request $request)
    {
        MedalTally::create($this->validateData($request));

        return redirect()->route('admin.medal-tallies.index')->with('flash', 'Medal-tally row created.');
    }

    public function edit(MedalTally $medalTally)
    {
        return view('admin.medal-tallies.edit', ['tally' => $medalTally]);
    }

    public function update(Request $request, MedalTally $medalTally)
    {
        $medalTally->update($this->validateData($request, $medalTally));

        return redirect()->route('admin.medal-tallies.index')->with('flash', "Medal tally for {$medalTally->state} updated.");
    }

    public function destroy(MedalTally $medalTally)
    {
        $medalTally->delete();

        return redirect()->route('admin.medal-tallies.index')->with('flash', 'Medal-tally row deleted.');
    }

    private function validateData(Request $request, ?MedalTally $medalTally = null): array
    {
        return $request->validate([
            'state' => ['required', 'string', 'max:120', Rule::unique('medal_tallies', 'state')->ignore($medalTally)],
            'gold' => ['required', 'integer', 'min:0'],
            'silver' => ['required', 'integer', 'min:0'],
            'bronze' => ['required', 'integer', 'min:0'],
        ]);
    }
}
