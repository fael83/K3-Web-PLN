<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Apd;
use App\Models\AuditChecklist;
use App\Models\AuditLog;
use App\Models\Hazard;
use App\Models\HealthProgram;
use App\Models\Incident;
use App\Models\SopStep;
use App\Models\TeamMember;
use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $role = $user->role;

        // ── Employee: redirect ke halaman insiden (spec §5.2) ──────────
        if ($role === 'employee') {
            return redirect()->route('admin.incident.index')
                ->with('info', 'Akses dashboard tidak tersedia untuk employee. Silakan lapor insiden.');
        }

        // ══════════════════════════════════════════════════════════════
        // LANGKAH 3.1 — Filter params
        // Hanya aktif untuk sys_admin, k3_manager, k3_officer
        // ══════════════════════════════════════════════════════════════
        $canFilter = in_array($role, ['sys_admin', 'k3_manager', 'k3_officer']);

        $filterDept   = $canFilter ? $request->input('dept')   : null;
        $filterFrom   = $canFilter ? $request->input('from')   : null;
        $filterTo     = $canFilter ? $request->input('to')     : null;
        $filterStatus = $canFilter ? $request->input('status') : null;

        $filters = compact('filterDept', 'filterFrom', 'filterTo', 'filterStatus');

        // ══════════════════════════════════════════════════════════════
        // LANGKAH 1.4 — Scope department_head + gabung filter §3.1
        // ══════════════════════════════════════════════════════════════
        $isDeptScoped = ($role === 'department_head' && $user->department_id);
        $deptId       = $user->department_id;

        // Mulai base query lalu terapkan semua scope & filter sekaligus
        $baseIncident = Incident::query();

        // Scope dept_head: hanya insiden dari departemennya (aktif setelah migration 1.1)
        if ($isDeptScoped) {
            $baseIncident->where('department_id', $deptId);
        }

        // Filter dari panel dashboard (hanya sys_admin / k3_manager / k3_officer)
        if ($filterDept)   { $baseIncident->where('department_id', $filterDept); }
        if ($filterFrom)   { $baseIncident->whereDate('incident_date', '>=', $filterFrom); }
        if ($filterTo)     { $baseIncident->whereDate('incident_date', '<=', $filterTo); }
        if ($filterStatus) { $baseIncident->where('status', $filterStatus); }

        // ── KPI Summary ───────────────────────────────────────────────
        $openIncidents  = (clone $baseIncident)->whereIn('status', ['open', 'investigasi'])->count();
        $totalIncidents = (clone $baseIncident)->count();

        $lastIncident = (clone $baseIncident)
            ->whereNotNull('incident_date')
            ->latest('incident_date')
            ->first();
        $safeDays = $lastIncident
            ? (int) Carbon::parse($lastIncident->incident_date)->diffInDays(now())
            : null;

        $openCapa = (clone $baseIncident)
            ->whereIn('status', ['open', 'investigasi'])
            ->whereNull('corrective_action')
            ->count();

        $latestAudit  = AuditChecklist::with('items')->latest()->first();
        $openFindings = $latestAudit
            ? $latestAudit->items->whereIn('status', ['major_nc', 'minor_nc'])->count()
            : 0;

        // ── Resource Counts ───────────────────────────────────────────
        $counts = [
            'apd'      => Apd::count(),
            'sop'      => SopStep::count(),
            'hazard'   => Hazard::count(),
            'incident' => $totalIncidents,
            'team'     => TeamMember::count(),
            'health'   => HealthProgram::count(),
        ];

        // ── Tren Insiden 12 Bulan ─────────────────────────────────────
        $trend     = ['labels' => [], 'data' => [], 'data_prev' => []];
        $start     = Carbon::now()->startOfMonth()->subMonths(11);
        $startPrev = (clone $start)->subYear();

        $monthly = (clone $baseIncident)
            ->select(
                DB::raw("to_char(incident_date, 'YYYY-MM') as ym"),
                DB::raw('count(*) as total')
            )
            ->whereNotNull('incident_date')
            ->where('incident_date', '>=', $start->toDateString())
            ->groupBy('ym')
            ->pluck('total', 'ym');

        $monthlyPrev = (clone $baseIncident)
            ->select(
                DB::raw("to_char(incident_date, 'YYYY-MM') as ym"),
                DB::raw('count(*) as total')
            )
            ->whereNotNull('incident_date')
            ->where('incident_date', '>=', $startPrev->toDateString())
            ->where('incident_date', '<', $start->toDateString())
            ->groupBy('ym')
            ->pluck('total', 'ym');

        for ($i = 0; $i < 12; $i++) {
            $month  = (clone $start)->addMonths($i);
            $monthP = (clone $startPrev)->addMonths($i);
            $trend['labels'][]    = $month->translatedFormat('M Y');
            $trend['data'][]      = (int) ($monthly[$month->format('Y-m')] ?? 0);
            $trend['data_prev'][] = (int) ($monthlyPrev[$monthP->format('Y-m')] ?? 0);
        }

        // ── Insiden per Tipe ──────────────────────────────────────────
        $incidentByType = (clone $baseIncident)
            ->select('incident_type', DB::raw('count(*) as total'))
            ->groupBy('incident_type')
            ->pluck('total', 'incident_type');

        $typeChart = ['labels' => [], 'data' => []];
        foreach (Incident::TYPES as $key => $label) {
            $typeChart['labels'][] = $label;
            $typeChart['data'][]   = (int) ($incidentByType[$key] ?? 0);
        }

        // ── Status Insiden (doughnut) ─────────────────────────────────
        $incidentByStatus = (clone $baseIncident)
            ->select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status');

        $statusChart = ['labels' => [], 'data' => []];
        foreach (Incident::STATUSES as $key => $label) {
            $statusChart['labels'][] = $label;
            $statusChart['data'][]   = (int) ($incidentByStatus[$key] ?? 0);
        }

        // ── Bahaya per Kategori (selalu institution-wide, tidak di-filter) ─
        $hazardByCategory = Hazard::select('category', DB::raw('count(*) as total'))
            ->groupBy('category')
            ->pluck('total', 'category');

        $hazardChart = ['labels' => [], 'data' => []];
        foreach (Hazard::CATEGORIES as $key => $label) {
            $hazardChart['labels'][] = $label;
            $hazardChart['data'][]   = (int) ($hazardByCategory[$key] ?? 0);
        }

        // ── Audit Summary ─────────────────────────────────────────────
        $auditSummary = [
            'total'       => AuditChecklist::count(),
            'completed'   => AuditChecklist::where('status', 'completed')->count(),
            'in_progress' => AuditChecklist::where('status', 'in_progress')->count(),
            'draft'       => AuditChecklist::where('status', 'draft')->count(),
        ];

        // ── Aktivitas Terbaru ─────────────────────────────────────────
        $recentIncidents = (clone $baseIncident)->latest('created_at')->take(5)->get();
        $recentAuditLogs = AuditLog::with('user')->latest('created_at')->take(5)->get();

        // ── Statistik Dokumen ─────────────────────────────────────────
        $docStats = null;
        if (in_array($role, ['sys_admin', 'k3_manager', 'k3_officer', 'auditor', 'department_head', 'viewer'])) {
            $docStats = [
                'total'    => Document::count(),
                'approved' => Document::where('status', 'approved')->count(),
                'draft'    => Document::where('status', 'draft')->count(),
                'review'   => Document::where('status', 'review')->count(),
            ];
        }

        return view('admin.dashboard', compact(
            'user', 'role',
            'counts',
            'openIncidents', 'openCapa', 'openFindings', 'safeDays',
            'trend', 'typeChart', 'statusChart', 'hazardChart',
            'auditSummary',
            'recentIncidents', 'recentAuditLogs',
            'docStats',
            'filters', 'canFilter',   // ← §3.1: dipakai oleh panel filter di view
        ));
    }
}