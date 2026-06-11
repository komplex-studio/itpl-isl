# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## What this is

A **Laravel 13** web application for the **Indian Sports League (ISL)** — a national multi-sport
competition platform. It currently ships **two client-facing prototypes** that share one codebase,
data model, and design system:

1. **Public site** — league marketing/results site: home, sports, schedule, event hubs, live
   knockout **brackets**, **medal-tally standings**, **athlete self-registration** (issues a unique
   ISL ID), **certificate verification**, and news.
2. **Admin panel** (`/admin`, login-gated) — organiser console: dashboard, registration
   approvals, event/fixture management with inline **result entry**, athletes, sports,
   certificates, news.

The data is **seeded demo content** (a generic multi-sport season across 8 disciplines) so both
prototypes are fully navigable without manual data entry. This is a demo build, not production, but
the admin is **fully CRUD-capable**: every entity (sports, events, news, athletes, certificates,
registrations, fixtures, medal tally) has working create / edit / delete, on top of the inline
quick-actions (registration approve/reject → `registrations.status`, fixture result entry →
`fixtures.result`).

## Commands

```bash
# First-time / after pulling: install deps
composer install
npm install

# Build the demo database (SQLite) with all seed data — run this to reset to a clean demo state
php artisan migrate:fresh --seed

# Run it (two options)
php artisan serve              # http://127.0.0.1:8000  (standalone PHP server)
#  …or browse via XAMPP Apache at the docroot path, with `npm run dev` for assets

# Frontend assets (Tailwind v4 + Alpine via Vite)
npm run dev                    # hot-reload dev server — use while developing views
npm run build                  # one-off production build (required before serving without `npm run dev`)

# Tests / quality
php artisan test               # PHPUnit (config: phpunit.xml)
php artisan test --filter=SomeTest        # single test
vendor/bin/pint                # Laravel Pint code formatter
```

```bash
# Export a shareable static HTML snapshot to docs/prototype/ (open by double-click, no server)
php artisan serve --port=8099     # one terminal
php export-prototype.php           # another — renders each page, localises assets, rewires links
```

**Admin login:** `admin@isl.test` / `password` (seeded). Public registration and certificate
verification need no login.

**Demo certificate codes** start at `ISL-CERT-2026-00801`.

## Stack & key conventions

- **Laravel 13 / PHP 8.4**, **SQLite** (`database/database.sqlite`) for the prototype — chosen for
  zero-config portability. Switch `DB_CONNECTION` in `.env` for MySQL/XAMPP if needed.
  ⚠️ Because of SQLite, **avoid MySQL-only SQL** (e.g. `FIELD()`); do ordering/grouping that SQLite
  can't express in PHP via collection methods instead (see `EventController::schedule`).
- **Tailwind CSS v4** (`@tailwindcss/vite`, no `tailwind.config.js`). The design system lives in
  `resources/css/app.css`:
  - Brand color tokens are defined in `@theme` — **`ink`** (navy), **`saffron`** (accent),
    **`victory`** (green): a subtle Indian-tricolour palette. Use these tokens, not raw hex.
  - Reusable classes (`.btn`, `.card`, `.chip`, `.input`, `.eyebrow`, `.container-x`, …) are defined
    with **`@utility`**, NOT `@layer components`. In Tailwind v4 you cannot `@apply` a class defined
    in `@layer` — chaining (`btn-primary { @apply btn … }`) only works for `@utility`-defined
    classes. Follow that pattern when adding shared classes.
  - Numeric weights `font-600/700/800/900` are custom `@utility` aliases (Tailwind's defaults are
    `font-bold` etc.). Headings use the `Archivo` display font (`font-display`); body is
    `Instrument Sans`. Both are bundled offline via the Vite fonts plugin (`vite.config.js`).
- **Alpine.js** powers the only client interactivity (mobile nav, admin sidebar drawer). It's
  imported in `resources/js/app.js`. `[x-cloak]` is handled in `app.css`.
- **Guest redirect:** auth-protected routes send guests to `admin.login` — configured in
  `bootstrap/app.php` via `redirectGuestsTo()` (there is no route named `login`; don't add one
  expecting the framework default).

## Architecture

Standard Laravel MVC, organised by audience:

- **Routes** — all in `routes/web.php`. Public routes at top; admin under a
  `prefix('admin')->name('admin.')` group, with write actions behind `middleware('auth')`.
- **Controllers** — `app/Http/Controllers/` for public; `app/Http/Controllers/Admin/` for the panel
  (note the `as AdminXController` import aliases in `web.php` since names collide).
- **Views** — Blade under `resources/views/`:
  - `layouts/public.blade.php` + `admin/layout.blade.php` are the two shells.
  - `components/` holds anonymous Blade components reused across both: `brand-mark`, `site-nav`,
    `site-footer`, `page-header`, **`avatar`** (initials + gradient, no image files), and
    **`status-badge`** (maps a status string → labelled pill; the single source of truth for status
    styling — reuse it rather than hand-rolling badges).
  - `public/` and `admin/` mirror the controller split.
- **Image-led landing concept (client-approved).** The public site is photo-driven: `sports`,
  `events` and `news` each carry a nullable **`image`** column (an absolute, hotlink-stable photo
  URL — Unsplash for generic disciplines, **Wikimedia Commons** for the India-specific Kabaddi and
  Kusti so they read true). The curated set lives in `DatabaseSeeder::IMAGES`. The existing
  **`gradient`** column is kept as a graceful fallback — every photo `<div>` layers the image over
  its gradient (`bg-cover bg-center bg-gradient-to-br …`), so the layout survives a failed/missing
  image. There are still **no uploaded image files in the repo** — images are remote URLs only.
- **Athlete avatars remain initials on gradients** (no photos) via the `avatar` component.

### Data model (`app/Models`, migrations `database/migrations/2026_01_01_*`)

- `Sport` → has many `Event`. `format` is `knockout|league`; `icon` is an emoji; `color` is a brand
  token name; `image` is the photo URL. The season's eight disciplines are **Boxing, Athletics,
  Kabaddi, Karate, Football, Volleyball, Kusti and Badminton**.
- `Event` → belongs to `Sport`; has many `Registration`, `Fixture`, `Certificate`. `status` is
  `upcoming|ongoing|completed`. `date_range` accessor formats the start/end span; `image` is the
  hero photo (falls back to the sport's image / the `gradient` column in views).
- `Athlete` → has many `Registration`, `Certificate`. **Route-keyed by `code`** (the unique ISL ID,
  e.g. `ISL26-004213`). `initials` accessor drives the avatar component.
- `Registration` → `Athlete`×`Event` entry; `status` is `pending|approved|rejected`.
- `Fixture` — a single match/bout (table named **`fixtures`**, deliberately not `matches`, to dodge
  the MySQL `MATCH` reserved word). Brackets are modelled as fixtures grouped by `round_order`
  (1=earliest round). `status` is `scheduled|live|completed`. The seeder builds an 8-athlete
  single-elimination bracket via `DatabaseSeeder::buildKnockout()`.
- `MedalTally` — per-state gold/silver/bronze; powers the standings page (`total` accessor).
- `Certificate` — **route-keyed by `number`**; `type` is `participation|winner|runner_up|bronze`.
- `News` — `$table = 'news'`, route-keyed by `slug`.

`DatabaseSeeder` is the single seeder and the authoritative source of demo data — extend it (don't
add ad-hoc seeders) when you need more sample content, and re-run `migrate:fresh --seed`.

## When extending

- Adding a public page: route in `web.php` → controller method → Blade in `public/` extending
  `layouts.public`; lead with an `<x-page-header>`.
- Adding an admin screen: add to the `auth` group in `web.php`, add the controller under `Admin/`,
  add the nav entry in the `$nav` array in `admin/layout.blade.php`, and extend `admin.layout`.
- Admin CRUD pattern: register with `Route::resource(...)->except(['show'])`; controllers stay thin
  (validate → slug/code/number auto-gen → flash → redirect to index). Views follow
  `admin/<entity>/{index,create,edit}.blade.php` where create & edit both `@include` a shared
  `form.blade.php` (passed `$action`, `$method`, `$submit`). Index rows use `<x-admin.delete-button>`
  (confirm + DELETE form) and link to `…edit`. Validation errors render from the layout's `$errors`
  block; success uses `session('flash')`.
- Rebuild assets (`npm run build`, or run `npm run dev`) after adding new Tailwind classes — v4
  scans templates at build time, and unscanned classes silently produce no CSS.
