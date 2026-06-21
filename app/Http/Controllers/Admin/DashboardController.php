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
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // ── KPI Summary ──────────────────────────────────────────────
        $openIncidents   = Incident::whereIn('status', ['open', 'investigasi'])->count();
        $totalIncidents  = Incident::count();

        // Safe Days: hari sejak insiden terakhir
        $lastIncident = Incident::whereNotNull('incident_date')->latest('incident_date')->first();
        $safeDays = $lastIncident
            ? (int) Carbon::parse($lastIncident->incident_date)->diffInDays(now())
            : null;

        // Open CAPA: insiden open/investigasi yang punya corrective_action kosong
        $openCapa = Incident::whereIn('status', ['open', 'investigasi'])
            ->whereNull('corrective_action')
            ->count();

        // Audit: open findings (major + minor NC dari audit terakhir)
        $latestAudit = AuditChecklist::with('items')->latest()->first();
        $openFindings = $latestAudit
            ? $latestAudit->items->whereIn('status', ['major_nc', 'minor_nc'])->count()
            : 0;

        // Counts
        $counts = [
            'apd'      => Apd::count(),
            'sop'      => SopStep::count(),
            'hazard'   => Hazard::count(),
            'incident' => $totalIncidents,
            'team'     => TeamMember::count(),
            'health'   => HealthProgram::count(),
        ];

        // ── Tren Insiden 12 Bulan ────────────────────────────────────
        $trend = ['labels' => [], 'data' => [], 'data_prev' => []];
        $start = Carbon::now()->startOfMonth()->subMonths(11);

        $monthly = Incident::select(
                DB::raw("to_char(incident_date, 'YYYY-MM') as ym"),
                DB::raw('count(*) as total')
            )
            ->whereNotNull('incident_date')
            ->where('incident_date', '>=', $start->toDateString())
            ->groupBy('ym')
            ->pluck('total', 'ym');

        // Tahun lalu (untuk YoY)
        $startPrev = (clone $start)->subYear();
        $monthlyPrev = Incident::select(
                DB::raw("to_char(incident_date, 'YYYY-MM') as ym"),
                DB::raw('count(*) as total')
            )
            ->whereNotNull('incident_date')
            ->where('incident_date', '>=', $startPrev->toDateString())
            ->where('incident_date', '<', $start->toDateString())
            ->groupBy('ym')
            ->pluck('total', 'ym');

        for ($i = 0; $i < 12; $i++) {
            $month     = (clone $start)->addMonths($i);
            $monthPrev = (clone $startPrev)->addMonths($i);
            $key       = $month->format('Y-m');
            $keyPrev   = $monthPrev->format('Y-m');
            $trend['labels'][]    = $month->translatedFormat('M Y');
            $trend['data'][]      = (int) ($monthly[$key] ?? 0);
            $trend['data_prev'][] = (int) ($monthlyPrev[$keyPrev] ?? 0);
        }

        // ── Insiden per Tipe (untuk bar chart) ───────────────────────
        $incidentByType = Incident::select('incident_type', DB::raw('count(*) as total'))
            ->groupBy('incident_type')
            ->pluck('total', 'incident_type');

        $typeChart = ['labels' => [], 'data' => []];
        foreach (Incident::TYPES as $key => $label) {
            $typeChart['labels'][] = $label;
            $typeChart['data'][]   = (int) ($incidentByType[$key] ?? 0);
        }

        // ── Status Insiden (doughnut) ─────────────────────────────────
        $incidentByStatus = Incident::select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status');

        $statusChart = ['labels' => [], 'data' => []];
        foreach (Incident::STATUSES as $key => $label) {
            $statusChart['labels'][] = $label;
            $statusChart['data'][]   = (int) ($incidentByStatus[$key] ?? 0);
        }

        // ── Bahaya per Kategori ───────────────────────────────────────
        $hazardByCategory = Hazard::select('category', DB::raw('count(*) as total'))
            ->groupBy('category')
            ->pluck('total', 'category');

        $hazardChart = ['labels' => [], 'data' => []];
        foreach (Hazard::CATEGORIES as $key => $label) {
            $hazardChart['labels'][] = $label;
            $hazardChart['data'][]   = (int) ($hazardByCategory[$key] ?? 0);
        }

        // ── Audit Checklist Summary ───────────────────────────────────
        $auditSummary = [
            'total'       => AuditChecklist::count(),
            'completed'   => AuditChecklist::where('status', 'completed')->count(),
            'in_progress' => AuditChecklist::where('status', 'in_progress')->count(),
            'draft'       => AuditChecklist::where('status', 'draft')->count(),
        ];

        // ── Aktivitas Terbaru ─────────────────────────────────────────
        $recentIncidents  = Incident::latest('created_at')->take(5)->get();
        $recentAuditLogs  = AuditLog::with('user')->latest('created_at')->take(5)->get();

        return view('admin.dashboard', compact(
            'counts',
            'openIncidents',
            'openCapa',
            'openFindings',
            'safeDays',
            'trend',
            'typeChart',
            'statusChart',
            'hazardChart',
            'auditSummary',
            'recentIncidents',
            'recentAuditLogs'
        ));
    }
}
