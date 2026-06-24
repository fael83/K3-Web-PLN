<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Incident;
use App\Models\Hazard;
use App\Models\AuditChecklist;
use App\Models\Document;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Auth;
use App\Exports\IncidentExport;
use App\Exports\DashboardExport;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class ExportController extends Controller
{
    /**
     * Export laporan insiden ke PDF
     * Akses: sys_admin, k3_manager, k3_officer, department_head, auditor
     */
    public function incidentPdf(Request $request)
    {
        $user  = Auth::user();
        $query = Incident::with('department', 'reporter')->orderByDesc('incident_date');

        // Scope per departemen
        if ($user->role === 'department_head' && $user->department_id) {
            $query->where('department_id', $user->department_id);
        }

        // Filter opsional dari query string
        if ($request->filled('from')) {
            $query->whereDate('incident_date', '>=', $request->from);
        }
        if ($request->filled('to')) {
            $query->whereDate('incident_date', '<=', $request->to);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $incidents  = $query->get();
        $exportedAt = now()->translatedFormat('d F Y H:i');
        $department = $user->department?->name ?? 'Semua Departemen';

        $pdf = Pdf::loadView('admin.exports.incident-pdf', compact(
            'incidents', 'exportedAt', 'department', 'user'
        ))->setPaper('a4', 'landscape');

        return $pdf->download("laporan-insiden-{$exportedAt}.pdf");
    }

    /**
     * Export laporan insiden ke Excel
     */
    public function incidentExcel(Request $request)
    {
        $filters = $request->only(['from', 'to', 'status']);
        $filename = 'laporan-insiden-' . now()->format('Y-m-d') . '.xlsx';

        return Excel::download(new IncidentExport($filters, Auth::user()), $filename);
    }

    /**
     * Export ringkasan dashboard ke PDF
     * Akses: sys_admin, k3_manager, auditor
     */
    public function dashboardPdf(Request $request)
    {
        $incidents      = Incident::all();
        $openIncidents  = Incident::whereIn('status', ['open', 'investigasi'])->count();
        $openCapa       = Incident::whereIn('status', ['open', 'investigasi'])->whereNull('corrective_action')->count();
        $totalHazards   = Hazard::count();
        $auditSummary   = [
            'total'     => AuditChecklist::count(),
            'completed' => AuditChecklist::where('status', 'completed')->count(),
        ];
        $exportedAt     = now()->translatedFormat('d F Y H:i');

        $pdf = Pdf::loadView('admin.exports.dashboard-pdf', compact(
            'openIncidents', 'openCapa', 'totalHazards',
            'auditSummary', 'exportedAt'
        ));

        return $pdf->download("ringkasan-k3-{$exportedAt}.pdf");
    }
}