<?php

use App\Http\Controllers\Admin\ApdController;
use App\Http\Controllers\Admin\AuditChecklistController;
use App\Http\Controllers\Admin\AuditLogController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DocumentController;
use App\Http\Controllers\Admin\ExportController;
use App\Http\Controllers\Admin\HazardController;
use App\Http\Controllers\Admin\HealthProgramController;
use App\Http\Controllers\Admin\IncidentController;
use App\Http\Controllers\Admin\OrganizationController;
use App\Http\Controllers\Admin\SopController;
use App\Http\Controllers\Admin\TeamMemberController;
use App\Http\Controllers\Admin\UserManagementController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\PublicController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TeamK3Controller;
use App\Http\Controllers\Admin\MonitoringFormController;
use App\Http\Controllers\Admin\MonitoringSubmissionController;

// ── Halaman Publik ─────────────────────────────────────────────────────────
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

// ── Autentikasi ────────────────────────────────────────────────────────────
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->middleware('guest')->name('login.attempt');
Route::post('/logout', [LoginController::class, 'logout'])->middleware('auth')->name('logout');

// ── Panel Admin ────────────────────────────────────────────────────────────
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

    // Monitoring Forms
    Route::middleware(
        'role:sys_admin,k3_manager,k3_officer'
    )->group(function () {

        Route::resource(
            'monitoring-forms',
            MonitoringFormController::class
        )->only([
            'index',
            'create',
            'store',
            'show'
        ]);

        Route::post(
            '/monitoring-forms/{monitoring_form}/fields',
            [MonitoringFormController::class, 'storeField']
        )->name('monitoring-forms.fields.store');

        Route::get(
    '/monitoring-submissions',
    [MonitoringSubmissionController::class,'adminIndex']
)->name('monitoring-submissions.admin.index');

Route::get(
    '/monitoring-submissions/{submission}',
    [MonitoringSubmissionController::class,'show']
)->name('monitoring-submissions.show');

Route::post(
    '/monitoring-submissions/{submission}/approve',
    [MonitoringSubmissionController::class,'approve']
)->name('monitoring-submissions.approve');

Route::post(
    '/monitoring-submissions/{submission}/reject',
    [MonitoringSubmissionController::class,'reject']
)->name('monitoring-submissions.reject');
    });


    Route::middleware(
    'role:employee'
    )->group(function () {

        Route::get(
            '/my-monitoring-forms',
            [MonitoringSubmissionController::class, 'index']
        )->name('monitoring-submissions.index');

        Route::get(
            '/my-monitoring-forms/{form}',
            [MonitoringSubmissionController::class, 'create']
        )->name('monitoring-submissions.create');

        Route::post(
            '/my-monitoring-forms/{form}',
            [MonitoringSubmissionController::class, 'store']
        )->name('monitoring-submissions.store');
    });

    // ─── USER & ORGANIZATION MANAGEMENT ─────────────────────────────────────
    Route::middleware('role:sys_admin')->group(function () {
        Route::resource('users', UserManagementController::class);

        Route::patch('/users/{user}/toggle-active', [UserManagementController::class, 'toggleActive'])
            ->name('users.toggle-active');

        Route::patch('/users/{user}/reset-password', [UserManagementController::class, 'resetPassword'])
            ->name('users.reset-password');
    });

    // k3_officer + k3_manager + sys_admin: lihat daftar user per departemen
    Route::middleware('role:sys_admin,k3_manager,k3_officer')->group(function () {
        Route::get('/users-by-dept', [UserManagementController::class, 'byDepartment'])
            ->name('users.by-department');
    });

    // ── ORGANIZATION ───────────────────────────────────────────────────────
    // Tampilan read-only: sys_admin, k3_manager, k3_officer, auditor
    Route::middleware('role:sys_admin,k3_manager,k3_officer,auditor')->group(function () {
        Route::get('/organization', [OrganizationController::class, 'index'])
            ->name('organization.index');
    });

    // Write: hanya sys_admin
    Route::middleware('role:sys_admin')->group(function () {
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

    // ── AUDIT ──────────────────────────────────────────────────────────────
    Route::middleware('role:sys_admin,k3_manager,k3_officer,auditor')->group(function () {
        Route::get('/audit-log', [AuditLogController::class, 'index'])->name('audit.index');
        Route::resource('audit-checklist', AuditChecklistController::class)->names('audit-checklist');
        Route::post('/audit-checklist/{auditChecklist}/item', [AuditChecklistController::class, 'addItem'])
            ->name('audit-checklist.item.store');
        Route::put('/audit-checklist/{auditChecklist}/item/{item}', [AuditChecklistController::class, 'updateItem'])
            ->name('audit-checklist.item.update');
        Route::delete('/audit-checklist/{auditChecklist}/item/{item}', [AuditChecklistController::class, 'destroyItem'])
            ->name('audit-checklist.item.destroy');
        Route::get('/audit-evidence', [AuditChecklistController::class, 'evidenceIndex'])
            ->name('audit-evidence.index');
        Route::post('/audit-evidence/generate', [AuditChecklistController::class, 'evidenceGenerate'])
            ->name('audit-evidence.generate');
    });

    // ── DOKUMEN ────────────────────────────────────────────────────────────
    Route::prefix('documents')->name('documents.')->group(function () {
        Route::get('/', [DocumentController::class, 'index'])->name('index')
            ->middleware('role:sys_admin,k3_manager,k3_officer,department_head,employee,auditor,viewer');
        Route::get('/create', [DocumentController::class, 'create'])->name('create')
            ->middleware('role:sys_admin,k3_manager,k3_officer');
        Route::post('/', [DocumentController::class, 'store'])->name('store')
            ->middleware('role:sys_admin,k3_manager,k3_officer');
        Route::get('/{document}/edit', [DocumentController::class, 'edit'])->name('edit')
            ->middleware('role:sys_admin,k3_manager,k3_officer')->whereNumber('document');
        Route::put('/{document}', [DocumentController::class, 'update'])->name('update')
            ->middleware('role:sys_admin,k3_manager,k3_officer')->whereNumber('document');
        Route::post('/{document}/submit-review', [DocumentController::class, 'submitReview'])->name('submitReview')
            ->middleware('role:sys_admin,k3_manager,k3_officer')->whereNumber('document');
        Route::post('/{document}/approve', [DocumentController::class, 'approve'])->name('approve')
            ->middleware('role:sys_admin,k3_manager')->whereNumber('document');
        Route::post('/{document}/reject', [DocumentController::class, 'reject'])->name('reject')
            ->middleware('role:sys_admin,k3_manager')->whereNumber('document');
        Route::post('/{document}/revise', [DocumentController::class, 'revise'])->name('revise')
            ->middleware('role:sys_admin,k3_manager,k3_officer')->whereNumber('document');
        Route::delete('/{document}', [DocumentController::class, 'destroy'])->name('destroy')
            ->middleware('role:sys_admin,k3_manager')->whereNumber('document');
        Route::get('/{document}', [DocumentController::class, 'show'])->name('show')
            ->middleware('role:sys_admin,k3_manager,k3_officer,department_head,employee,auditor,viewer')
            ->whereNumber('document');
    });

    // ── EXPORT ─────────────────────────────────────────────────────────────
    Route::prefix('export')->name('export.')->group(function () {
        Route::middleware('role:sys_admin,k3_manager,k3_officer,department_head,auditor')->group(function () {
            Route::get('/incident/pdf',   [ExportController::class, 'incidentPdf'])->name('incident.pdf');
            Route::get('/incident/excel', [ExportController::class, 'incidentExcel'])->name('incident.excel');
        });
        Route::middleware('role:sys_admin,k3_manager,auditor')->group(function () {
            Route::get('/dashboard/pdf', [ExportController::class, 'dashboardPdf'])->name('dashboard.pdf');
        });
    });

});

// ── Debug ──────────────────────────────────────────────────────────────────
Route::get('/cek-php', fn() => [
    'php_version' => phpversion(),
    'loaded_ini'  => php_ini_loaded_file(),
    'pdo_pgsql'   => extension_loaded('pdo_pgsql'),
    'pgsql'       => extension_loaded('pgsql'),
]);
Route::middleware(['auth','role:sys_admin'])->get('/tes-role', fn() => 'Middleware Role Berhasil');
Route::middleware('auth')->get('/cek-role', fn() => [
    'name'  => Auth::user()->name,
    'email' => Auth::user()->email,
    'role'  => Auth::user()->role,
]);