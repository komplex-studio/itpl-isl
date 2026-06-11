<?php

use App\Http\Controllers\Admin\AthleteController as AdminAthleteController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\CertificateController as AdminCertificateController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\EventController as AdminEventController;
use App\Http\Controllers\Admin\FixtureController as AdminFixtureController;
use App\Http\Controllers\Admin\MedalTallyController as AdminMedalTallyController;
use App\Http\Controllers\Admin\NewsController as AdminNewsController;
use App\Http\Controllers\Admin\RegistrationController as AdminRegistrationController;
use App\Http\Controllers\Admin\SportController as AdminSportController;
use App\Http\Controllers\CertificateController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\SportController;
use App\Http\Controllers\StandingsController;
use Illuminate\Support\Facades\Route;

/*
| Public site
*/
Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/sports', [SportController::class, 'index'])->name('sports.index');
Route::get('/sports/{sport}', [SportController::class, 'show'])->name('sports.show');

Route::get('/schedule', [EventController::class, 'schedule'])->name('schedule');
Route::get('/events/{event}', [EventController::class, 'show'])->name('events.show');
Route::get('/events/{event}/bracket', [EventController::class, 'bracket'])->name('events.bracket');

Route::get('/standings', [StandingsController::class, 'index'])->name('standings');

Route::get('/register', [RegistrationController::class, 'create'])->name('register.create');
Route::post('/register', [RegistrationController::class, 'store'])->name('register.store');
Route::get('/register/success/{athlete}', [RegistrationController::class, 'success'])->name('register.success');

Route::get('/certificates', [CertificateController::class, 'index'])->name('certificates.index');
Route::post('/certificates/verify', [CertificateController::class, 'verify'])->name('certificates.verify');
Route::get('/certificates/{certificate}', [CertificateController::class, 'show'])->name('certificates.show');

Route::get('/news', [NewsController::class, 'index'])->name('news.index');
Route::get('/news/{news}', [NewsController::class, 'show'])->name('news.show');

/*
| Admin panel
*/
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('login', [AuthController::class, 'show'])->name('login');
    Route::post('login', [AuthController::class, 'login'])->name('login.attempt');
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');

    Route::middleware('auth')->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

        // Full CRUD for every content entity.
        Route::resource('sports', AdminSportController::class)->except(['show']);
        Route::resource('events', AdminEventController::class)->except(['show']);
        Route::resource('athletes', AdminAthleteController::class)->except(['show']);
        Route::resource('news', AdminNewsController::class)->except(['show']);
        Route::resource('certificates', AdminCertificateController::class)->except(['show']);
        Route::resource('medal-tallies', AdminMedalTallyController::class)
            ->except(['show'])->parameters(['medal-tallies' => 'medalTally']);

        // Registrations & fixtures keep their inline quick-actions alongside full CRUD.
        Route::resource('registrations', AdminRegistrationController::class)->except(['show']);
        Route::patch('registrations/{registration}/status', [AdminRegistrationController::class, 'status'])->name('registrations.status');

        Route::resource('fixtures', AdminFixtureController::class)->except(['show']);
        Route::patch('fixtures/{fixture}/result', [AdminFixtureController::class, 'result'])->name('fixtures.result');
    });
});
