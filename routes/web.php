<?php

use App\Http\Controllers\Admin\ApdController;
use App\Http\Controllers\Admin\AuditLogController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\HazardController;
use App\Http\Controllers\Admin\HealthProgramController;
use App\Http\Controllers\Admin\IncidentController;
use App\Http\Controllers\Admin\SopController;
use App\Http\Controllers\Admin\TeamMemberController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\PublicController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Halaman Publik (tanpa login)
|--------------------------------------------------------------------------
*/
Route::controller(PublicController::class)->group(function () {
    Route::get('/', 'home')->name('public.home');
    Route::get('/profil', 'profil')->name('public.profil');
    Route::get('/identifikasi-bahaya', 'bahaya')->name('public.bahaya');
    Route::get('/risiko-k3', 'risiko')->name('public.risiko');
    Route::get('/apd', 'apd')->name('public.apd');
    Route::get('/sop', 'sop')->name('public.sop');
    Route::get('/prosedur-evakuasi', 'evakuasi')->name('public.evakuasi');
    Route::get('/program-kesehatan', 'kesehatan')->name('public.kesehatan');
    Route::get('/struktur-tim-k3', 'tim')->name('public.tim');
    Route::get('/denah-lokasi', 'denah')->name('public.denah');
    Route::get('/kesimpulan-saran', 'kesimpulan')->name('public.kesimpulan');
});

/*
|--------------------------------------------------------------------------
| Autentikasi Admin
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('login.attempt');
});

Route::post('/logout', [LoginController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

/*
|--------------------------------------------------------------------------
| Panel Admin (wajib login)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('home');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('apd', ApdController::class)->except('show');
    Route::resource('sop', SopController::class)->except('show');
    Route::resource('hazard', HazardController::class)->except('show');
    Route::resource('incident', IncidentController::class)->except('show');
    Route::resource('team', TeamMemberController::class)->except('show');
    Route::resource('health', HealthProgramController::class)->except('show');

    Route::get('/audit-log', [AuditLogController::class, 'index'])->name('audit.index');
});

Route::get('/cek-php', function () {
    return [
        'php_version' => phpversion(),
        'loaded_ini' => php_ini_loaded_file(),
        'pdo_pgsql' => extension_loaded('pdo_pgsql'),
        'pgsql' => extension_loaded('pgsql'),
    ];
});
