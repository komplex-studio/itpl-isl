@extends('layouts.public')
@section('title', 'Verify a Certificate')

@section('content')
    <x-page-header
        eyebrow="Certification"
        title="Verify a certificate"
        subtitle="Every ISL certificate carries a unique verification code. Enter it below to confirm authenticity and view the record."
        gradient="from-victory-600 to-ink-950" />

    <section class="container-x grid gap-10 py-14 lg:grid-cols-2">
        <div>
            <form method="POST" action="{{ route('certificates.verify') }}" class="card p-7">
                @csrf
                <label class="label" for="number">Certificate number</label>
                <input id="number" name="number" value="{{ old('number') }}" class="input font-mono" placeholder="ISL-CERT-2026-00801" required>
                @if (session('verify_error'))
                    <p class="mt-2 text-sm text-rose-600">{{ session('verify_error') }}</p>
                @endif
                <button type="submit" class="btn-primary mt-5 w-full">Verify certificate</button>
                <p class="mt-3 text-xs text-ink-400">Demo codes start at <button type="button" onclick="document.getElementById('number').value='ISL-CERT-2026-00801'" class="font-mono font-semibold text-saffron-600">ISL-CERT-2026-00801</button>.</p>
            </form>
        </div>

        <div>
            <h2 class="font-display text-lg font-800 text-ink-900">Recently issued</h2>
            <div class="mt-4 space-y-3">
                @foreach ($recent as $cert)
                    <a href="{{ route('certificates.show', $cert) }}" class="card flex items-center gap-4 p-4 transition hover:border-saffron-300">
                        <x-avatar :athlete="$cert->athlete" size="md" />
                        <div class="min-w-0 flex-1">
                            <p class="truncate text-sm font-semibold text-ink-900">{{ $cert->athlete->name }}</p>
                            <p class="truncate text-xs text-ink-500">{{ $cert->title }}</p>
                        </div>
                        <span class="font-mono text-xs text-ink-400">{{ $cert->number }}</span>
                    </a>
                @endforeach
            </div>
        </div>
    </section>
@endsection
