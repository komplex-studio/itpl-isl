<?php

use App\Http\Controllers\Admin\AthleteController as AdminAthleteController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\CertificateController as AdminCertificateController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\EventController as AdminEventController;
use App\Http\Controllers\Admin\FixtureController as AdminFixtureController;
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

        Route::get('sports', [AdminSportController::class, 'index'])->name('sports.index');
        Route::get('events', [AdminEventController::class, 'index'])->name('events.index');
        Route::get('athletes', [AdminAthleteController::class, 'index'])->name('athletes.index');

        Route::get('registrations', [AdminRegistrationController::class, 'index'])->name('registrations.index');
        Route::patch('registrations/{registration}', [AdminRegistrationController::class, 'update'])->name('registrations.update');

        Route::get('fixtures', [AdminFixtureController::class, 'index'])->name('fixtures.index');
        Route::patch('fixtures/{fixture}', [AdminFixtureController::class, 'update'])->name('fixtures.update');

        Route::get('certificates', [AdminCertificateController::class, 'index'])->name('certificates.index');
        Route::get('news', [AdminNewsController::class, 'index'])->name('news.index');
    });
});
