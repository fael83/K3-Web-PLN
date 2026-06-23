<?php

use App\Http\Controllers\Admin\ApdController;
use App\Http\Controllers\Admin\AuditLogController;
use App\Http\Controllers\Admin\AuditChecklistController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\HazardController;
use App\Http\Controllers\Admin\HealthProgramController;
use App\Http\Controllers\Admin\IncidentController;
use App\Http\Controllers\Admin\OrganizationController;
use App\Http\Controllers\Admin\SopController;
use App\Http\Controllers\Admin\TeamMemberController;
use App\Http\Controllers\Admin\UserManagementController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\PublicController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TeamK3Controller;

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
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');

Route::post('/login', [LoginController::class, 'login'])
    ->middleware('guest')
    ->name('login.attempt');

Route::post('/logout', [LoginController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

/*
|--------------------------------------------------------------------------
| Panel Admin (wajib login)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->prefix('admin')->name('admin.')->group(function () {

    // Dashboard
    Route::middleware('role:sys_admin,k3_manager,k3_officer,department_head,employee,auditor,viewer')->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('home');
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    });

    // APD, SOP, Hazard, Health
    Route::middleware('role:sys_admin,k3_manager,k3_officer')->group(function () {
        Route::resource('apd', ApdController::class)->except('show');
        Route::resource('sop', SopController::class)->except('show');
        Route::resource('hazard', HazardController::class)->except('show');
        Route::resource('health', HealthProgramController::class)->except('show');
    });

    // Incident
    Route::middleware('role:sys_admin,k3_manager,k3_officer,department_head,employee')->group(function () {
        Route::resource('incident', IncidentController::class)->except('show');
    });

    // Team K3
    Route::middleware('role:sys_admin,k3_manager,k3_officer')->group(function () {
        Route::resource('team', TeamMemberController::class)->except('show');
    });

    // ─── USER & ORGANIZATION MANAGEMENT ─────────────────────────────────────
    Route::middleware('role:sys_admin')->group(function () {

        // 6.2 User Management
        Route::resource('users', UserManagementController::class);
        Route::patch('/users/{user}/toggle-active', [UserManagementController::class, 'toggleActive'])
            ->name('users.toggle-active');
        Route::patch('/users/{user}/reset-password', [UserManagementController::class, 'resetPassword'])
            ->name('users.reset-password');

        // 6.2.1 Organization Structure
        Route::get('/organization', [OrganizationController::class, 'index'])
            ->name('organization.index');

        Route::post('/organization/division', [OrganizationController::class, 'storeDivision'])
            ->name('organization.division.store');
        Route::put('/organization/division/{division}', [OrganizationController::class, 'updateDivision'])
            ->name('organization.division.update');
        Route::delete('/organization/division/{division}', [OrganizationController::class, 'destroyDivision'])
            ->name('organization.division.destroy');

        Route::post('/organization/department', [OrganizationController::class, 'storeDepartment'])
            ->name('organization.department.store');
        Route::put('/organization/department/{department}', [OrganizationController::class, 'updateDepartment'])
            ->name('organization.department.update');
        Route::delete('/organization/department/{department}', [OrganizationController::class, 'destroyDepartment'])
            ->name('organization.department.destroy');

        Route::post('/organization/work-unit', [OrganizationController::class, 'storeWorkUnit'])
            ->name('organization.work-unit.store');
        Route::delete('/organization/work-unit/{workUnit}', [OrganizationController::class, 'destroyWorkUnit'])
            ->name('organization.work-unit.destroy');
    });
    // ─────────────────────────────────────────────────────────────────────────

    // ─── AUDIT SUPPORT ───────────────────────────────────────────────────────
    Route::middleware('role:sys_admin,k3_manager,k3_officer,auditor')->group(function () {

        // 6.6.1 Audit Trail
        Route::get('/audit-log', [AuditLogController::class, 'index'])
            ->name('audit.index');

        // 6.6.2 Audit Checklist
        Route::resource('audit-checklist', AuditChecklistController::class)
            ->names('audit-checklist');

        // Item per checklist
        Route::post('/audit-checklist/{auditChecklist}/item', [AuditChecklistController::class, 'addItem'])
            ->name('audit-checklist.item.store');
        Route::put('/audit-checklist/{auditChecklist}/item/{item}', [AuditChecklistController::class, 'updateItem'])
            ->name('audit-checklist.item.update');
        Route::delete('/audit-checklist/{auditChecklist}/item/{item}', [AuditChecklistController::class, 'destroyItem'])
            ->name('audit-checklist.item.destroy');

        // 6.6.3 Evidence Package
        Route::get('/audit-evidence', [AuditChecklistController::class, 'evidenceIndex'])
            ->name('audit-evidence.index');
        Route::post('/audit-evidence/generate', [AuditChecklistController::class, 'evidenceGenerate'])
            ->name('audit-evidence.generate');
    });
    // ─────────────────────────────────────────────────────────────────────────
});

/*
|--------------------------------------------------------------------------
| Debug / Testing
|--------------------------------------------------------------------------
*/
Route::get('/cek-php', function () {
    return [
        'php_version' => phpversion(),
        'loaded_ini' => php_ini_loaded_file(),
        'pdo_pgsql' => extension_loaded('pdo_pgsql'),
        'pgsql' => extension_loaded('pgsql'),
    ];
});

Route::middleware(['auth', 'role:sys_admin'])
    ->get('/tes-role', function () {
        return 'Middleware Role Berhasil';
    });

Route::middleware('auth')->get('/cek-role', function () {
    return [
        'name' => auth()->user()->name,
        'email' => auth()->user()->email,
        'role' => auth()->user()->role,
    ];
});
