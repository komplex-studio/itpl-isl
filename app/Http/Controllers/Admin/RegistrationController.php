<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Athlete;
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

    public function create()
    {
        return view('admin.registrations.create', $this->formData(new Registration(['status' => 'pending'])));
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);
        $data['registered_at'] = $data['registered_at'] ?? now();
        Registration::create($data);

        return redirect()->route('admin.registrations.index')->with('flash', 'Registration created.');
    }

    public function edit(Registration $registration)
    {
        return view('admin.registrations.edit', $this->formData($registration));
    }

    public function update(Request $request, Registration $registration)
    {
        $registration->update($this->validateData($request));

        return redirect()->route('admin.registrations.index')->with('flash', "Registration #{$registration->id} updated.");
    }

    public function destroy(Registration $registration)
    {
        $registration->delete();

        return redirect()->route('admin.registrations.index')->with('flash', 'Registration deleted.');
    }

    /** Inline approve/reject quick-action from the index list. */
    public function status(Request $request, Registration $registration)
    {
        $data = $request->validate([
            'status' => ['required', 'in:approved,rejected,pending'],
        ]);

        $registration->update(['status' => $data['status']]);

        return back()->with('flash', "Registration #{$registration->id} marked as {$data['status']}.");
    }

    private function formData(Registration $registration): array
    {
        return [
            'registration' => $registration,
            'athletes' => Athlete::orderBy('name')->get(),
            'events' => Event::with('sport')->orderBy('name')->get(),
        ];
    }

    private function validateData(Request $request): array
    {
        return $request->validate([
            'athlete_id' => ['required', 'exists:athletes,id'],
            'event_id' => ['required', 'exists:events,id'],
            'category' => ['required', 'string', 'max:120'],
            'status' => ['required', 'in:pending,approved,rejected'],
            'registered_at' => ['nullable', 'date'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);
    }
}
