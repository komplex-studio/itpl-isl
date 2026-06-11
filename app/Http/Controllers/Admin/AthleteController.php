<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Athlete;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AthleteController extends Controller
{
    /** Brand-token gradients available for the initials avatar. */
    private const TINTS = [
        'from-saffron-400 to-saffron-600',
        'from-ink-500 to-ink-800',
        'from-victory-400 to-victory-600',
        'from-saffron-500 to-ink-700',
        'from-ink-400 to-victory-600',
    ];

    public function index(Request $request)
    {
        $athletes = Athlete::withCount('registrations')
            ->when($request->q, fn ($query, $q) => $query->where(fn ($w) => $w->where('name', 'like', "%$q%")
                ->orWhere('code', 'like', "%$q%")
                ->orWhere('state', 'like', "%$q%")
            ))
            ->orderBy('name')
            ->paginate(20)
            ->withQueryString();

        return view('admin.athletes.index', [
            'athletes' => $athletes,
            'filters' => $request->only('q'),
        ]);
    }

    public function create()
    {
        return view('admin.athletes.create', ['athlete' => new Athlete, 'tints' => self::TINTS]);
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);
        $data['code'] = $this->nextCode();
        $data['avatar_tint'] = ($data['avatar_tint'] ?? null) ?: self::TINTS[0];
        $athlete = Athlete::create($data);

        return redirect()->route('admin.athletes.index')->with('flash', "Athlete {$athlete->name} added — ISL ID {$athlete->code}.");
    }

    public function edit(Athlete $athlete)
    {
        return view('admin.athletes.edit', ['athlete' => $athlete, 'tints' => self::TINTS]);
    }

    public function update(Request $request, Athlete $athlete)
    {
        $data = $this->validateData($request, $athlete);
        $data['avatar_tint'] = ($data['avatar_tint'] ?? null) ?: $athlete->avatar_tint;
        $athlete->update($data);

        return redirect()->route('admin.athletes.index')->with('flash', "Athlete {$athlete->name} updated.");
    }

    public function destroy(Athlete $athlete)
    {
        $athlete->delete();

        return redirect()->route('admin.athletes.index')->with('flash', 'Athlete deleted.');
    }

    private function validateData(Request $request, ?Athlete $athlete = null): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'gender' => ['required', Rule::in(['M', 'F'])],
            'dob' => ['required', 'date', 'before:today'],
            'state' => ['required', 'string', 'max:120'],
            'city' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:160', Rule::unique('athletes', 'email')->ignore($athlete)],
            'phone' => ['nullable', 'string', 'max:30'],
            'avatar_tint' => ['nullable', 'string', 'max:80'],
            'bio' => ['nullable', 'string', 'max:1000'],
        ]);
    }

    private function nextCode(): string
    {
        $last = Athlete::where('code', 'like', 'ISL26-%')
            ->orderByDesc('code')
            ->value('code');
        $n = $last ? ((int) substr($last, 6)) + 1 : 4001;

        return 'ISL26-'.str_pad((string) $n, 6, '0', STR_PAD_LEFT);
    }
}
