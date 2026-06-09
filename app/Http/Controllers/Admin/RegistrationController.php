<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Registration;
use Illuminate\Http\Request;

class RegistrationController extends Controller
{
    public function index(Request $request)
    {
        $registrations = Registration::with(['athlete', 'event.sport'])
            ->when($request->status, fn ($q, $s) => $q->where('status', $s))
            ->when($request->event, fn ($q, $e) => $q->where('event_id', $e))
            ->latest('registered_at')
            ->paginate(15)
            ->withQueryString();

        return view('admin.registrations.index', [
            'registrations' => $registrations,
            'events' => Event::orderBy('name')->get(),
            'filters' => $request->only('status', 'event'),
            'counts' => [
                'all' => Registration::count(),
                'pending' => Registration::where('status', 'pending')->count(),
                'approved' => Registration::where('status', 'approved')->count(),
                'rejected' => Registration::where('status', 'rejected')->count(),
            ],
        ]);
    }

    public function update(Request $request, Registration $registration)
    {
        $data = $request->validate([
            'status' => ['required', 'in:approved,rejected,pending'],
        ]);

        $registration->update(['status' => $data['status']]);

        return back()->with('flash', "Registration #{$registration->id} marked as {$data['status']}.");
    }
}
