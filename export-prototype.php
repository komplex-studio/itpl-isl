<?php

/**
 * Static prototype exporter.
 *
 * Renders the running Laravel app to self-contained .html files in docs/prototype/
 * so the prototype can be shared and opened by double-click (no PHP server needed).
 *
 * Usage:
 *   php artisan serve --port=8099        # in one terminal
 *   php export-prototype.php             # in another
 *
 * What it does:
 *   - logs into the admin panel (admin@isl.test / password)
 *   - fetches each page, copies the compiled CSS/fonts into docs/prototype/assets/
 *   - rewrites asset URLs to local paths and internal links to the matching .html file
 *   - swaps the Vite JS module for the Alpine CDN (ES modules don't load over file://)
 */

$base = 'http://127.0.0.1:8099';
$root = __DIR__;
$out = $root.'/docs/prototype';
$assetsSrc = $root.'/public/build/assets';
$assetsOut = $out.'/assets';

@mkdir($out, 0777, true);
@mkdir($assetsOut, 0777, true);

// Copy compiled assets (css + hashed fonts) verbatim — relative url() refs keep working.
foreach (glob($assetsSrc.'/*') as $f) {
    copy($f, $assetsOut.'/'.basename($f));
}

$athlete = 'ISL26-004001';
$news = 'indian-sports-league-2026-season-kicks-off-across-8-disciplines';
$event = 'national-boxing-championship-2026';

// path => output filename
$map = [
    "/events/$event/bracket" => 'bracket.html',
    "/events/$event" => 'event.html',
    '/sports/boxing' => 'sport-boxing.html',
    '/sports' => 'sports.html',
    '/schedule' => 'schedule.html',
    '/standings' => 'standings.html',
    "/register/success/$athlete" => 'register-success.html',
    '/register' => 'register.html',
    '/certificates/ISL-CERT-2026-00801' => 'certificate.html',
    '/certificates/verify' => 'certificates.html',
    '/certificates' => 'certificates.html',
    "/news/$news" => 'news-article.html',
    '/news' => 'news.html',
    '/admin/login' => 'admin-login.html',
    '/admin/logout' => 'admin-login.html',
    '/admin/registrations' => 'admin-registrations.html',
    '/admin/events' => 'admin-events.html',
    '/admin/fixtures' => 'admin-fixtures.html',
    '/admin/athletes' => 'admin-athletes.html',
    '/admin/sports' => 'admin-sports.html',
    '/admin/certificates' => 'admin-certificates.html',
    '/admin/news' => 'admin-news.html',
    '/admin' => 'admin-dashboard.html',
];

// Pages to actually fetch (url => output file). Public are public; admin needs login.
$pages = [
    '/' => 'index.html',
    '/sports' => 'sports.html',
    '/sports/boxing' => 'sport-boxing.html',
    '/schedule' => 'schedule.html',
    "/events/$event" => 'event.html',
    "/events/$event/bracket" => 'bracket.html',
    '/standings' => 'standings.html',
    '/register' => 'register.html',
    "/register/success/$athlete" => 'register-success.html',
    '/certificates' => 'certificates.html',
    '/certificates/ISL-CERT-2026-00801' => 'certificate.html',
    '/news' => 'news.html',
    "/news/$news" => 'news-article.html',
    '/admin/login' => 'admin-login.html',
    '/admin' => 'admin-dashboard.html',
    '/admin/registrations' => 'admin-registrations.html',
    '/admin/events' => 'admin-events.html',
    '/admin/fixtures' => 'admin-fixtures.html',
    '/admin/athletes' => 'admin-athletes.html',
    '/admin/sports' => 'admin-sports.html',
    '/admin/certificates' => 'admin-certificates.html',
    '/admin/news' => 'admin-news.html',
];

$cookie = tempnam(sys_get_temp_dir(), 'islc');

function http_get(string $url, string $cookie): string
{
    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_COOKIEJAR => $cookie,
        CURLOPT_COOKIEFILE => $cookie,
    ]);
    $body = curl_exec($ch);
    curl_close($ch);

    return $body ?: '';
}

// Log into admin so authed pages render.
$login = http_get("$base/admin/login", $cookie);
preg_match('/name="_token" value="([^"]+)"/', $login, $m);
$token = $m[1] ?? '';
$ch = curl_init("$base/admin/login");
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_COOKIEJAR => $cookie,
    CURLOPT_COOKIEFILE => $cookie,
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => http_build_query([
        '_token' => $token,
        'email' => 'admin@isl.test',
        'password' => 'password',
    ]),
]);
curl_exec($ch);
curl_close($ch);

// Sort link map longest-path-first so specific routes win over prefixes.
uksort($map, fn ($a, $b) => strlen($b) <=> strlen($a));

$alpine = '<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.1/dist/cdn.min.js"></script>';

// Forms can't POST over file:// — wire the primary CTAs to their next screen so the
// prototype is clickable end-to-end. [needle => replacement], applied per output file.
$special = [
    'admin-login.html' => [
        '<button class="btn-primary w-full">Sign in</button>'
            => '<a href="admin-dashboard.html" class="btn-primary w-full">Sign in</a>',
    ],
    'register.html' => [
        '<button type="submit" class="btn-primary w-full sm:w-auto">Submit registration</button>'
            => '<a href="register-success.html" class="btn-primary w-full sm:w-auto">Submit registration</a>',
    ],
    'certificates.html' => [
        '<button type="submit" class="btn-primary mt-5 w-full">Verify certificate</button>'
            => '<a href="certificate.html" class="btn-primary mt-5 w-full">Verify certificate</a>',
    ],
];

$count = 0;
foreach ($pages as $url => $file) {
    $html = http_get($base.$url, $cookie);
    if ($html === '') {
        echo "  ! failed: $url\n";
        continue;
    }

    // 1) assets -> local relative
    $html = str_replace("$base/build/assets/", 'assets/', $html);

    // 2) drop Vite module preload/script, inject Alpine CDN (modules can't load via file://)
    $html = preg_replace('#<link rel="modulepreload"[^>]*>#', '', $html);
    $html = preg_replace('#<script type="module"[^>]*></script>#', $alpine, $html);

    // 3) internal links -> local .html files
    foreach ($map as $path => $target) {
        $html = str_replace($base.$path, $target, $html);
    }
    // brand link / any bare base -> home
    $html = str_replace(["$base/", $base], ['index.html', 'index.html'], $html);

    // 4) per-page CTA wiring
    foreach ($special[$file] ?? [] as $needle => $replacement) {
        $html = str_replace($needle, $replacement, $html);
    }

    file_put_contents("$out/$file", $html);
    echo "  ✓ $file\n";
    $count++;
}

// Overview gallery — a simple index of every screen for easy sharing/navigation.
$groups = [
    'Public site' => [
        ['index.html', 'Landing page', 'Hero, sports, results, medal tally, news'],
        ['sports.html', 'Sports', 'All eight disciplines'],
        ['sport-boxing.html', 'Sport detail', 'Events within a sport'],
        ['schedule.html', 'Schedule & fixtures', 'Filterable events + bracket links'],
        ['event.html', 'Event hub', 'Overview, fixtures, participants'],
        ['bracket.html', 'Knockout bracket', 'Live single-elimination draw'],
        ['standings.html', 'Medal tally', 'State-by-state standings'],
        ['register.html', 'Athlete registration', 'Self-registration form'],
        ['register-success.html', 'Registration success', 'Generated ISL ID card'],
        ['certificates.html', 'Verify certificate', 'Verification lookup'],
        ['certificate.html', 'Certificate', 'Printable verified certificate'],
        ['news.html', 'News', 'Newsroom listing'],
        ['news-article.html', 'News article', 'Single story'],
    ],
    'Admin panel' => [
        ['admin-login.html', 'Login', 'Organiser console sign-in'],
        ['admin-dashboard.html', 'Dashboard', 'Stats, approvals, fixtures, medals'],
        ['admin-registrations.html', 'Registrations', 'Approve / reject entries'],
        ['admin-events.html', 'Events', 'Event management'],
        ['admin-fixtures.html', 'Fixtures & results', 'Inline result entry'],
        ['admin-athletes.html', 'Athletes', 'Athlete directory'],
        ['admin-sports.html', 'Sports', 'Discipline management'],
        ['admin-certificates.html', 'Certificates', 'Issued certificates'],
        ['admin-news.html', 'News', 'Content management'],
    ],
];

$css = basename(current(glob($assetsSrc.'/app-*.css')));
$cards = '';
foreach ($groups as $group => $items) {
    $cards .= '<h2 class="mt-12 mb-5 font-display text-2xl font-800 text-ink-900">'.$group.'</h2>';
    $cards .= '<div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">';
    foreach ($items as [$f, $title, $desc]) {
        $cards .= '<a href="'.$f.'" class="card group p-6 transition hover:-translate-y-1 hover:shadow-lg">'
            .'<p class="font-display text-lg font-800 text-ink-900 group-hover:text-saffron-600">'.$title.'</p>'
            .'<p class="mt-1 text-sm text-ink-500">'.$desc.'</p>'
            .'<p class="mt-4 font-mono text-xs text-ink-400">'.$f.'</p></a>';
    }
    $cards .= '</div>';
}

$overview = '<!DOCTYPE html><html lang="en"><head><meta charset="utf-8">'
    .'<meta name="viewport" content="width=device-width, initial-scale=1">'
    .'<title>ISL Prototype · Overview</title>'
    .'<link rel="stylesheet" href="assets/'.$css.'"></head>'
    .'<body class="bg-ink-50">'
    .'<header class="bg-ink-950 text-white"><div class="container-x py-14">'
    .'<p class="text-xs font-bold uppercase tracking-[0.2em] text-saffron-400">Client prototype</p>'
    .'<h1 class="mt-2 font-display text-4xl font-900">Indian Sports League — design prototype</h1>'
    .'<p class="mt-3 max-w-2xl text-ink-300">Static preview of every screen. Click any card to open it — '
    .'navigation, brackets and the registration flow are all clickable. Built on Laravel; these files '
    .'are an exported snapshot for sharing.</p>'
    .'<a href="index.html" class="btn-primary mt-6">Open the live landing page →</a>'
    .'</div></header>'
    .'<main class="container-x pb-20">'.$cards.'</main></body></html>';

file_put_contents("$out/overview.html", $overview);
echo "  ✓ overview.html\n";

@unlink($cookie);
echo "\nExported ".($count + 1)." pages to docs/prototype/\n";
