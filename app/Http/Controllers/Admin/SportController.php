<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Sport;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class SportController extends Controller
{
    public function index()
    {
        return view('admin.sports.index', [
            'sports' => Sport::withCount('events')->orderBy('name')->get(),
        ]);
    }

    public function create()
    {
        return view('admin.sports.create', ['sport' => new Sport]);
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);
        $data['slug'] = $this->uniqueSlug($data['name']);
        Sport::create($data);

        return redirect()->route('admin.sports.index')->with('flash', "Sport “{$data['name']}” created.");
    }

    public function edit(Sport $sport)
    {
        return view('admin.sports.edit', ['sport' => $sport]);
    }

    public function update(Request $request, Sport $sport)
    {
        $data = $this->validateData($request, $sport);
        $data['slug'] = $this->uniqueSlug($data['name'], $sport);
        $sport->update($data);

        return redirect()->route('admin.sports.index')->with('flash', "Sport “{$sport->name}” updated.");
    }

    public function destroy(Sport $sport)
    {
        if ($sport->events()->exists()) {
            return back()->with('flash', "Can’t delete “{$sport->name}” — it still has events. Remove them first.");
        }

        $sport->delete();

        return redirect()->route('admin.sports.index')->with('flash', 'Sport deleted.');
    }

    private function validateData(Request $request, ?Sport $sport = null): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:120', Rule::unique('sports', 'name')->ignore($sport)],
            'icon' => ['required', 'string', 'max:16'],
            'color' => ['required', Rule::in(['ink', 'saffron', 'victory'])],
            'format' => ['required', Rule::in(['knockout', 'league'])],
            'tagline' => ['nullable', 'string', 'max:160'],
            'image' => ['nullable', 'url', 'max:500'],
            'description' => ['nullable', 'string', 'max:2000'],
        ]);
    }

    private function uniqueSlug(string $name, ?Sport $sport = null): string
    {
        $base = Str::slug($name);
        $slug = $base;
        $i = 2;
        while (Sport::where('slug', $slug)->when($sport, fn ($q) => $q->whereKeyNot($sport->getKey()))->exists()) {
            $slug = $base.'-'.$i++;
        }

        return $slug;
    }
}
