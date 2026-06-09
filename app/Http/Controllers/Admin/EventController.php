<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;

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
}
