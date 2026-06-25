<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditChecklist;
use App\Models\AuditItem;
use App\Models\AuditLog;
use App\Models\Incident;
use App\Support\AuditLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuditChecklistController extends Controller
{
    /* ──────────────────────────────────────────────────────────
     | CHECKLIST (Index, Create, Store, Edit, Update, Destroy)
     ─────────────────────────────────────────────────────────── */

    public function index()
    {
        $audits = AuditChecklist::with('items', 'creator')
            ->latest()
            ->paginate(10);

        return view('admin.audit.checklist.index', compact('audits'));
    }

    public function create()
    {
        $defaultItems = $this->defaultChecklistItems();
        return view('admin.audit.checklist.form', [
            'audit'        => new AuditChecklist(),
            'defaultItems' => $defaultItems,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title'        => 'required|string|max:255',
            'description'  => 'nullable|string',
            'audit_type'   => 'required|in:internal,eksternal,smk3,iso45001',
            'audit_date'   => 'nullable|date',
            'auditor_name' => 'nullable|string|max:255',
            'status'       => 'required|in:draft,in_progress,completed',
            'items'        => 'nullable|array',
            'items.*.item_name'    => 'required|string|max:255',
            'items.*.description' => 'nullable|string',
        ]);

        $audit = AuditChecklist::create([
            'title'        => $data['title'],
            'description'  => $data['description'] ?? null,
            'audit_type'   => $data['audit_type'],
            'audit_date'   => $data['audit_date'] ?? null,
            'auditor_name' => $data['auditor_name'] ?? null,
            'status'       => $data['status'],
            'created_by'   => Auth::id(),
        ]);

        // Simpan item checklist
        if (!empty($data['items'])) {
            foreach ($data['items'] as $i => $item) {
                AuditItem::create([
                    'audit_checklist_id' => $audit->id,
                    'item_name'  => $item['item_name'],
                    'description'=> $item['description'] ?? null,
                    'status'     => 'pending',
                    'sort_order' => $i,
                ]);
            }
        }

        AuditLogger::record('Audit', 'create', "Membuat audit checklist: {$audit->title}");

        return redirect()->route('admin.audit-checklist.show', $audit)
            ->with('success', 'Audit checklist berhasil dibuat.');
    }

    public function show(AuditChecklist $auditChecklist)
    {
        $auditChecklist->load('items', 'creator');
        return view('admin.audit.checklist.show', compact('auditChecklist'));
    }

    public function edit(AuditChecklist $auditChecklist)
    {
        $auditChecklist->load('items');
        return view('admin.audit.checklist.form', [
            'audit'        => $auditChecklist,
            'defaultItems' => [],
        ]);
    }

    public function update(Request $request, AuditChecklist $auditChecklist)
    {
        $data = $request->validate([
            'title'        => 'required|string|max:255',
            'description'  => 'nullable|string',
            'audit_type'   => 'required|in:internal,eksternal,smk3,iso45001',
            'audit_date'   => 'nullable|date',
            'auditor_name' => 'nullable|string|max:255',
            'status'       => 'required|in:draft,in_progress,completed',
        ]);

        $auditChecklist->update($data);
        AuditLogger::record('Audit', 'update', "Memperbarui audit: {$auditChecklist->title}");

        return redirect()->route('admin.audit-checklist.show', $auditChecklist)
            ->with('success', 'Audit berhasil diperbarui.');
    }

    public function destroy(AuditChecklist $auditChecklist)
    {
        $title = $auditChecklist->title;
        $auditChecklist->delete();
        AuditLogger::record('Audit', 'delete', "Menghapus audit: {$title}");

        return redirect()->route('admin.audit-checklist.index')
            ->with('success', 'Audit berhasil dihapus.');
    }

    /* ──────────────────────────────────────────────────────────
     | AUDIT ITEMS (Update status & finding per item)
     ─────────────────────────────────────────────────────────── */

    public function updateItem(Request $request, AuditChecklist $auditChecklist, AuditItem $item)
    {
        $data = $request->validate([
            'status'            => 'required|in:pending,conformance,minor_nc,major_nc,observation',
            'finding'           => 'nullable|string',
            'corrective_action' => 'nullable|string',
            'evidence_ref'      => 'nullable|string|max:255',
        ]);

        $item->update($data);

        AuditLogger::record(
            'Audit',
            'update',
            "Update item audit [{$auditChecklist->title}]: {$item->item_name} → " . AuditItem::STATUSES[$data['status']]
        );

        return back()->with('success', 'Item audit berhasil diperbarui.');
    }

    public function addItem(Request $request, AuditChecklist $auditChecklist)
    {
        $data = $request->validate([
            'item_name'   => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $order = $auditChecklist->items()->max('sort_order') + 1;

        AuditItem::create([
            'audit_checklist_id' => $auditChecklist->id,
            'item_name'  => $data['item_name'],
            'description'=> $data['description'] ?? null,
            'status'     => 'pending',
            'sort_order' => $order,
        ]);

        return back()->with('success', 'Item berhasil ditambahkan.');
    }

    public function destroyItem(AuditChecklist $auditChecklist, AuditItem $item)
    {
        $item->delete();
        return back()->with('success', 'Item berhasil dihapus.');
    }

    /* ──────────────────────────────────────────────────────────
     | EVIDENCE PACKAGE
     ─────────────────────────────────────────────────────────── */

    public function evidenceIndex()
    {
        $audits = AuditChecklist::where('status', 'completed')
            ->orWhere('status', 'in_progress')
            ->latest()
            ->get();

        return view('admin.audit.evidence.index', compact('audits'));
    }

    public function evidenceGenerate(Request $request)
    {
        $request->validate([
            'audit_id'  => 'required|exists:audit_checklists,id',
            'date_from' => 'nullable|date',
            'date_to'   => 'nullable|date',
        ]);

        $audit = AuditChecklist::with('items', 'creator')->findOrFail($request->audit_id);

        // Kumpulkan audit logs relevan
        $logQuery = AuditLog::with('user')->latest('created_at');
        if ($request->filled('date_from')) {
            $logQuery->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $logQuery->whereDate('created_at', '<=', $request->date_to);
        }
        $logs = $logQuery->take(100)->get();

        // Kumpulkan insiden pada periode tersebut
        $incidentQuery = Incident::query();
        if ($request->filled('date_from')) {
            $incidentQuery->whereDate('incident_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $incidentQuery->whereDate('incident_date', '<=', $request->date_to);
        }
        $incidents = $incidentQuery->get();

        AuditLogger::record('Audit', 'export', "Generate evidence package: {$audit->title}");

        return view('admin.audit.evidence.report', compact('audit', 'logs', 'incidents', 'request'));
    }

    /* ──────────────────────────────────────────────────────────
     | HELPER
     ─────────────────────────────────────────────────────────── */

    private function defaultChecklistItems(): array
    {
        return [
            ['item_name' => 'SOP K3 tersedia dan mutakhir', 'description' => 'Periksa apakah SOP K3 sudah didokumentasikan dan diperbarui'],
            ['item_name' => 'APD tersedia dan layak pakai', 'description' => 'Verifikasi ketersediaan dan kondisi Alat Pelindung Diri'],
            ['item_name' => 'Pelatihan K3 telah dilaksanakan', 'description' => 'Bukti pelaksanaan pelatihan K3 kepada karyawan'],
            ['item_name' => 'Laporan insiden/kecelakaan kerja tercatat', 'description' => 'Sistem pelaporan insiden berjalan dengan baik'],
            ['item_name' => 'Inspeksi rutin K3 dilakukan', 'description' => 'Jadwal dan rekam inspeksi rutin tersedia'],
            ['item_name' => 'Identifikasi bahaya (Hazard) terdokumentasi', 'description' => 'Dokumen HIRA/IBPR tersedia dan terkini'],
            ['item_name' => 'Tindakan perbaikan (CAPA) dilaksanakan', 'description' => 'Corrective & Preventive Action atas temuan audit sebelumnya'],
            ['item_name' => 'Program kesehatan karyawan berjalan', 'description' => 'MCU, promosi kesehatan, dan program kebugaran'],
            ['item_name' => 'Prosedur evakuasi darurat tersedia', 'description' => 'Denah evakuasi, titik kumpul, dan simulasi bencana'],
            ['item_name' => 'Tim K3 aktif dan berstruktur', 'description' => 'P2K3 atau unit K3 memiliki tugas pokok dan fungsi jelas'],
        ];
    }
}