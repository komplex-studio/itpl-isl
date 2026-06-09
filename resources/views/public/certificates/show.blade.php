@extends('layouts.public')
@section('title', 'Certificate '.$certificate->number)

@php
    $badge = [
        'winner' => ['🥇', 'Champion', 'from-saffron-400 to-saffron-600'],
        'runner_up' => ['🥈', 'Runner-up', 'from-ink-300 to-ink-500'],
        'bronze' => ['🥉', 'Third place', 'from-amber-500 to-amber-700'],
        'participation' => ['🎖️', 'Participant', 'from-victory-400 to-victory-600'],
    ][$certificate->type] ?? ['🎖️', 'Participant', 'from-victory-400 to-victory-600'];
@endphp

@section('content')
    <section class="container-x py-14">
        <div class="mx-auto max-w-3xl">
            <div class="mb-6 flex items-center justify-center gap-2 text-sm text-victory-700">
                <span class="inline-grid h-6 w-6 place-items-center rounded-full bg-victory-500/15">✓</span>
                Verified · authentic ISL certificate
            </div>

            {{-- Certificate --}}
            <div class="overflow-hidden rounded-3xl border-4 border-double border-saffron-300 bg-white shadow-xl">
                <div class="bg-gradient-to-r from-ink-950 via-ink-900 to-ink-950 px-8 py-6 text-center text-white">
                    <x-brand-mark :dark="true" class="justify-center" />
                </div>
                <div class="px-8 py-12 text-center sm:px-16">
                    <p class="text-5xl">{{ $badge[0] }}</p>
                    <p class="mt-4 text-xs font-bold uppercase tracking-[0.3em] text-saffron-600">Certificate of {{ $badge[1] }}</p>
                    <p class="mt-6 text-sm text-ink-500">This is to certify that</p>
                    <h1 class="mt-2 font-display text-4xl font-900 text-ink-900">{{ $certificate->athlete->name }}</h1>
                    <p class="mt-1 text-sm text-ink-500">{{ $certificate->athlete->state }} · {{ $certificate->athlete->code }}</p>
                    <p class="mx-auto mt-6 max-w-lg text-ink-600">
                        {{ $certificate->title }} at the
                        <span class="font-semibold text-ink-900">{{ $certificate->event->name }}</span>,
                        held in {{ $certificate->event->city }}, {{ $certificate->event->state }}.
                    </p>

                    <div class="mt-10 flex items-end justify-between border-t border-ink-100 pt-6 text-left">
                        <div>
                            <p class="text-xs uppercase tracking-wider text-ink-400">Issued</p>
                            <p class="font-semibold text-ink-800">{{ $certificate->issued_at->format('d M Y') }}</p>
                        </div>
                        <div class="text-center">
                            <div class="grid h-16 w-16 place-items-center rounded-lg border border-ink-200 bg-ink-50 text-[8px] leading-tight text-ink-400">QR<br>VERIFY</div>
                        </div>
                        <div class="text-right">
                            <p class="text-xs uppercase tracking-wider text-ink-400">Verification no.</p>
                            <p class="font-mono text-sm font-semibold text-ink-800">{{ $certificate->number }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-6 flex justify-center gap-3">
                <button onclick="window.print()" class="btn-primary">Download / print</button>
                <a href="{{ route('certificates.index') }}" class="btn-ghost">Verify another</a>
            </div>
        </div>
    </section>
@endsection
