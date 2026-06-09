<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Sport;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function schedule(Request $request)
    {
        $events = Event::with('sport')
            ->when($request->sport, fn ($q, $slug) => $q->whereHas('sport', fn ($s) => $s->where('slug', $slug)))
            ->when($request->status, fn ($q, $status) => $q->where('status', $status))
            ->orderBy('start_date')
            ->get()
            ->sortBy(fn ($e) => [['ongoing' => 0, 'upcoming' => 1, 'completed' => 2][$e->status] ?? 3, $e->start_date->timestamp])
            ->values();

        // events that have a published bracket — for the #brackets section
        $bracketEvents = Event::with('sport')
            ->whereHas('fixtures')
            ->get()
            ->sortByDesc(fn ($e) => $e->status === 'ongoing' ? 1 : 0)
            ->values();

        return view('public.schedule', [
            'events' => $events,
            'sports' => Sport::orderBy('name')->get(),
            'bracketEvents' => $bracketEvents,
            'filters' => $request->only('sport', 'status'),
        ]);
    }

    public function show(Event $event)
    {
        $event->load([
            'sport',
            'registrations.athlete',
            'fixtures' => fn ($q) => $q->orderBy('round_order')->orderBy('slot'),
            'fixtures.athleteA',
            'fixtures.athleteB',
            'fixtures.winner',
        ]);

        return view('public.events.show', [
            'event' => $event,
            'participants' => $event->registrations->where('status', 'approved')->pluck('athlete')->unique('id')->values(),
        ]);
    }

    public function bracket(Event $event)
    {
        $event->load([
            'sport',
            'fixtures' => fn ($q) => $q->orderBy('round_order')->orderBy('slot'),
            'fixtures.athleteA',
            'fixtures.athleteB',
            'fixtures.winner',
        ]);

        return view('public.events.bracket', [
            'event' => $event,
            'rounds' => $event->fixtures->groupBy('round_order'),
        ]);
    }
}
