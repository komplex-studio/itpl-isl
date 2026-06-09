<?php

namespace App\Http\Controllers;

use App\Models\Athlete;
use App\Models\Event;
use App\Models\Registration;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class RegistrationController extends Controller
{
    public function create()
    {
        return view('public.register.create', [
            'events' => Event::with('sport')->where('registration_open', true)->orderBy('start_date')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'gender' => ['required', 'in:M,F'],
            'dob' => ['required', 'date', 'before:today'],
            'state' => ['required', 'string', 'max:80'],
            'city' => ['nullable', 'string', 'max:80'],
            'email' => ['required', 'email', 'max:160'],
            'phone' => ['required', 'string', 'max:20'],
            'event_id' => ['required', 'exists:events,id'],
            'category' => ['required', 'string', 'max:80'],
            'consent' => ['accepted'],
        ]);

        $athlete = Athlete::create([
            'code' => $this->generateCode(),
            'name' => $data['name'],
            'gender' => $data['gender'],
            'dob' => $data['dob'],
            'state' => $data['state'],
            'city' => $data['city'] ?? null,
            'email' => $data['email'],
            'phone' => $data['phone'],
            'avatar_tint' => 'from-saffron-400 to-saffron-600',
            'bio' => null,
        ]);

        Registration::create([
            'athlete_id' => $athlete->id,
            'event_id' => $data['event_id'],
            'category' => $data['category'],
            'status' => 'pending',
            'registered_at' => now(),
        ]);

        return redirect()->route('register.success', $athlete);
    }

    public function success(Athlete $athlete)
    {
        $athlete->load('registrations.event.sport');

        return view('public.register.success', [
            'athlete' => $athlete,
            'registration' => $athlete->registrations->last(),
        ]);
    }

    private function generateCode(): string
    {
        $last = Athlete::where('code', 'like', 'ISL26-%')->orderByDesc('code')->value('code');
        $next = $last ? ((int) Str::after($last, 'ISL26-')) + 1 : 4001;

        return 'ISL26-'.str_pad((string) $next, 6, '0', STR_PAD_LEFT);
    }
}
