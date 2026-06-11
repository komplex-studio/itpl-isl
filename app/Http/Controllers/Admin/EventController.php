<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Sport;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class EventController extends Controller
{
    public function index()
    {
        return view('admin.events.index', [
            'events' => Event::with('sport')
                ->withCount(['registrations', 'fixtures'])
                ->orderBy('start_date')
                ->get(),
        ]);
    }

    public function create()
    {
        return view('admin.events.create', ['event' => new Event(['season' => '2026', 'status' => 'upcoming']), 'sports' => $this->sports()]);
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);
        $data['slug'] = $this->uniqueSlug($data['name']);
        Event::create($data);

        return redirect()->route('admin.events.index')->with('flash', "Event “{$data['name']}” created.");
    }

    public function edit(Event $event)
    {
        return view('admin.events.edit', ['event' => $event, 'sports' => $this->sports()]);
    }

    public function update(Request $request, Event $event)
    {
        $data = $this->validateData($request, $event);
        $data['slug'] = $this->uniqueSlug($data['name'], $event);
        $event->update($data);

        return redirect()->route('admin.events.index')->with('flash', "Event “{$event->name}” updated.");
    }

    public function destroy(Event $event)
    {
        $event->delete();

        return redirect()->route('admin.events.index')->with('flash', 'Event deleted.');
    }

    private function sports()
    {
        return Sport::orderBy('name')->get();
    }

    private function validateData(Request $request, ?Event $event = null): array
    {
        $data = $request->validate([
            'sport_id' => ['required', 'exists:sports,id'],
            'name' => ['required', 'string', 'max:160'],
            'season' => ['required', 'string', 'max:10'],
            'city' => ['required', 'string', 'max:120'],
            'state' => ['required', 'string', 'max:120'],
            'venue' => ['required', 'string', 'max:160'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'status' => ['required', 'in:upcoming,ongoing,completed'],
            'registration_open' => ['nullable', 'boolean'],
            'prize_pool' => ['required', 'integer', 'min:0'],
            'image' => ['nullable', 'url', 'max:500'],
            'gradient' => ['nullable', 'string', 'max:120'],
            'summary' => ['nullable', 'string', 'max:2000'],
        ]);

        $data['registration_open'] = $request->boolean('registration_open');
        $data['gradient'] = ($data['gradient'] ?? null) ?: 'from-ink-800 to-ink-950';

        return $data;
    }

    private function uniqueSlug(string $name, ?Event $event = null): string
    {
        $base = Str::slug($name);
        $slug = $base;
        $i = 2;
        while (Event::where('slug', $slug)->when($event, fn ($q) => $q->whereKeyNot($event->getKey()))->exists()) {
            $slug = $base.'-'.$i++;
        }

        return $slug;
    }
}
