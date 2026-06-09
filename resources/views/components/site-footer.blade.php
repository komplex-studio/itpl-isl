<footer class="mt-24 bg-ink-950 text-ink-300">
    <div class="container-x grid gap-10 py-16 md:grid-cols-4">
        <div class="md:col-span-1">
            <x-brand-mark :dark="true" />
            <p class="mt-4 max-w-xs text-sm leading-relaxed text-ink-400">
                A unified digital platform for India's national multi-sport competition — registration, scheduling, live results and verified certification.
            </p>
        </div>

        <div>
            <h4 class="mb-4 text-sm font-bold uppercase tracking-wider text-white">Compete</h4>
            <ul class="space-y-2.5 text-sm">
                <li><a href="{{ route('sports.index') }}" class="hover:text-saffron-400">Sports</a></li>
                <li><a href="{{ route('schedule') }}" class="hover:text-saffron-400">Schedule &amp; fixtures</a></li>
                <li><a href="{{ route('standings') }}" class="hover:text-saffron-400">Medal tally</a></li>
                <li><a href="{{ route('register.create') }}" class="hover:text-saffron-400">Athlete registration</a></li>
            </ul>
        </div>

        <div>
            <h4 class="mb-4 text-sm font-bold uppercase tracking-wider text-white">League</h4>
            <ul class="space-y-2.5 text-sm">
                <li><a href="{{ route('news.index') }}" class="hover:text-saffron-400">News &amp; media</a></li>
                <li><a href="{{ route('certificates.index') }}" class="hover:text-saffron-400">Verify a certificate</a></li>
                <li><a href="{{ route('admin.login') }}" class="hover:text-saffron-400">Organiser login</a></li>
            </ul>
        </div>

        <div>
            <h4 class="mb-4 text-sm font-bold uppercase tracking-wider text-white">Partners</h4>
            <div class="flex flex-wrap gap-2 text-xs font-semibold">
                <span class="rounded-lg bg-white/5 px-3 py-2">Sports Authority of India</span>
                <span class="rounded-lg bg-white/5 px-3 py-2">Khelo India</span>
                <span class="rounded-lg bg-white/5 px-3 py-2">Fit India</span>
                <span class="rounded-lg bg-white/5 px-3 py-2">National Federations</span>
            </div>
        </div>
    </div>

    <div class="border-t border-white/10">
        <div class="container-x flex flex-col items-center justify-between gap-3 py-6 text-xs text-ink-500 sm:flex-row">
            <p>© 2026 Indian Sports League. Prototype for client demonstration.</p>
            <p class="flex items-center gap-1.5">
                <span class="inline-block h-2 w-2 rounded-full bg-victory-400"></span>
                Tricolour by design · Built for Indian sport
            </p>
        </div>
    </div>
</footer>
