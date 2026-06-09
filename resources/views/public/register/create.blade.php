@extends('layouts.public')
@section('title', 'Athlete Registration')

@php
    $states = ['Andhra Pradesh','Assam','Bihar','Chhattisgarh','Delhi','Goa','Gujarat','Haryana','Himachal Pradesh','Jharkhand','Karnataka','Kerala','Madhya Pradesh','Maharashtra','Manipur','Odisha','Punjab','Rajasthan','Tamil Nadu','Telangana','Uttar Pradesh','Uttarakhand','West Bengal'];
    $categories = ['Men 57kg','Men 63kg','Men 71kg','Women 50kg','Women 60kg','Freestyle 65kg','Greco-Roman 60kg','Senior Men','Senior Women','100m','400m','Javelin','Long Jump','Singles','Doubles','49kg','67kg','81kg'];
@endphp

@section('content')
    <x-page-header
        eyebrow="Join the league"
        title="Athlete registration"
        subtitle="Create your athlete profile and get a unique ISL ID instantly. Approved entries appear on the event roster within 48 hours." />

    <section class="container-x grid gap-10 py-14 lg:grid-cols-3">
        <div class="lg:col-span-2">
            @if ($errors->any())
                <div class="mb-6 rounded-2xl border border-rose-200 bg-rose-50 p-4 text-sm text-rose-700">
                    Please correct the highlighted fields below.
                </div>
            @endif

            <form method="POST" action="{{ route('register.store') }}" class="card space-y-7 p-7">
                @csrf

                <div>
                    <h2 class="font-display text-lg font-800 text-ink-900">Personal details</h2>
                    <div class="mt-4 grid gap-5 sm:grid-cols-2">
                        <div class="sm:col-span-2">
                            <label class="label" for="name">Full name</label>
                            <input id="name" name="name" value="{{ old('name') }}" class="input" placeholder="e.g. Arjun Singh" required>
                            @error('name') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="label" for="gender">Gender</label>
                            <select id="gender" name="gender" class="input" required>
                                <option value="M" @selected(old('gender') === 'M')>Male</option>
                                <option value="F" @selected(old('gender') === 'F')>Female</option>
                            </select>
                        </div>
                        <div>
                            <label class="label" for="dob">Date of birth</label>
                            <input id="dob" type="date" name="dob" value="{{ old('dob') }}" max="2012-12-31" class="input" required>
                            @error('dob') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="label" for="state">State / UT</label>
                            <select id="state" name="state" class="input" required>
                                <option value="">Select state…</option>
                                @foreach ($states as $st)
                                    <option value="{{ $st }}" @selected(old('state') === $st)>{{ $st }}</option>
                                @endforeach
                            </select>
                            @error('state') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="label" for="city">City</label>
                            <input id="city" name="city" value="{{ old('city') }}" class="input" placeholder="e.g. Rohtak">
                        </div>
                        <div>
                            <label class="label" for="email">Email</label>
                            <input id="email" type="email" name="email" value="{{ old('email') }}" class="input" placeholder="you@example.com" required>
                            @error('email') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="label" for="phone">Phone</label>
                            <input id="phone" name="phone" value="{{ old('phone') }}" class="input" placeholder="+91 9XXXXXXXXX" required>
                            @error('phone') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                <div class="border-t border-ink-100 pt-6">
                    <h2 class="font-display text-lg font-800 text-ink-900">Event & category</h2>
                    <div class="mt-4 grid gap-5 sm:grid-cols-2">
                        <div>
                            <label class="label" for="event_id">Event</label>
                            <select id="event_id" name="event_id" class="input" required>
                                <option value="">Choose an event…</option>
                                @foreach ($events as $event)
                                    <option value="{{ $event->id }}" @selected((string) old('event_id') === (string) $event->id)>
                                        {{ $event->sport->icon }} {{ $event->name }} — {{ $event->city }}
                                    </option>
                                @endforeach
                            </select>
                            @error('event_id') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="label" for="category">Category / weight class</label>
                            <input id="category" name="category" list="cats" value="{{ old('category') }}" class="input" placeholder="e.g. Men 57kg" required>
                            <datalist id="cats">
                                @foreach ($categories as $c)<option value="{{ $c }}">@endforeach
                            </datalist>
                            @error('category') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                <label class="flex items-start gap-3 border-t border-ink-100 pt-6 text-sm text-ink-600">
                    <input type="checkbox" name="consent" value="1" class="mt-0.5 h-5 w-5 rounded border-ink-300 text-saffron-500 focus:ring-saffron-400" required>
                    <span>I confirm the information is accurate and accept the league's code of conduct and anti-doping policy.</span>
                </label>
                @error('consent') <p class="-mt-3 text-xs text-rose-600">{{ $message }}</p> @enderror

                <button type="submit" class="btn-primary w-full sm:w-auto">Submit registration</button>
            </form>
        </div>

        <aside class="space-y-5">
            <div class="card p-6">
                <h3 class="font-display text-lg font-800 text-ink-900">What happens next</h3>
                <ol class="mt-4 space-y-4 text-sm">
                    @foreach (['Instant unique ISL ID generated','Organiser reviews & approves entry','Athlete added to the event roster','Digital certificate issued post-event'] as $i => $step)
                        <li class="flex gap-3">
                            <span class="grid h-7 w-7 shrink-0 place-items-center rounded-full bg-saffron-100 font-display text-xs font-800 text-saffron-700">{{ $i + 1 }}</span>
                            <span class="text-ink-600">{{ $step }}</span>
                        </li>
                    @endforeach
                </ol>
            </div>
            <div class="rounded-2xl bg-ink-950 p-6 text-white">
                <p class="text-3xl">🪪</p>
                <h3 class="mt-3 font-display text-lg font-800">One ID, every event</h3>
                <p class="mt-2 text-sm text-ink-300">Your ISL ID works across all sports and seasons — register once, compete anywhere.</p>
            </div>
        </aside>
    </section>
@endsection
