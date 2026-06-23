<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\AuditLogger;
use App\Http\Controllers\Controller;
use App\Models\Document;
use App\Models\DocumentVersion;
use App\Services\SupabaseStorageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DocumentController extends Controller
{
    protected SupabaseStorageService $storage;

    public function __construct(SupabaseStorageService $storage)
    {
        $this->storage = $storage;
    }

    // ─── INDEX ───────────────────────────────────────────────────────────
    public function index(Request $request)
    {
        $user = Auth::user();

        $query = Document::with(['uploader', 'approver']);

        if (in_array($user->role, ['sys_admin', 'k3_manager'])) {
            // Bisa lihat semua
        } elseif ($user->role === 'k3_officer') {
            $query->where(function ($q) use ($user) {
                $q->where('status', 'approved')
                  ->orWhere('uploaded_by', $user->id);
            });
        } elseif ($user->role === 'auditor') {
            $query->whereIn('status', ['approved', 'obsolete']);
        } else {
            $query->where('status', 'approved');
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'ilike', "%{$search}%")
                  ->orWhere('document_number', 'ilike', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('department')) {
            $query->where('owner_department', $request->department);
        }

        $documents = $query->orderByDesc('created_at')
            ->paginate(15)
            ->withQueryString();

        $expiringSoon = Document::expiringSoon()->count();

        return view('admin.documents.index', compact('documents', 'expiringSoon'));
    }

    // ─── CREATE ──────────────────────────────────────────────────────────
    public function create()
    {
        $this->authorizeDocumentManager();

        return view('admin.documents.create');
    }

    // ─── STORE ───────────────────────────────────────────────────────────
    public function store(Request $request)
    {
        $this->authorizeDocumentManager();

        $request->validate([
            'title'            => 'required|string|max:255',
            'document_number'  => 'required|string|max:255',
            'category'         => 'required|in:policy,standard,procedure,legal,form_template,record,emergency',
            'owner_department' => 'nullable|string|max:255',
            'effective_date'   => 'nullable|date',
            'review_date'      => 'nullable|date|after_or_equal:effective_date',
            'description'      => 'nullable|string',
            'file'             => 'required|file|mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png|max:51200',
        ]);

        $file = $request->file('file');
        $fileUrl = $this->storage->upload($file, 'documents');

        if (!$fileUrl) {
            return back()->withErrors([
                'file' => 'Gagal mengupload file ke storage.',
            ])->withInput();
        }

        $document = Document::create([
            'title'            => $request->title,
            'document_number'  => $request->document_number,
            'category'         => $request->category,
            'owner_department' => $request->owner_department,
            'status'           => 'draft',
            'file_url'         => $fileUrl,
            'file_name'        => $file->getClientOriginalName(),
            'file_type'        => $file->getClientOriginalExtension(),
            'file_size'        => $file->getSize(),
            'revision_number'  => 1,
            'effective_date'   => $request->effective_date,
            'review_date'      => $request->review_date,
            'description'      => $request->description,
            'uploaded_by'      => Auth::id(),
        ]);

        AuditLogger::record(
            'documents',
            'create',
            "Upload dokumen: {$document->title} ({$document->document_number})",
            $document->id
        );

        return redirect()->route('admin.documents.show', $document)
            ->with('success', 'Dokumen berhasil diupload sebagai draft.');
    }

    // ─── SHOW ────────────────────────────────────────────────────────────
    public function show(Document $document)
    {
        $this->authorizeViewDocument($document);

        $document->load(['uploader', 'approver', 'versions.uploader']);

        $revisionHistory = Document::with(['uploader', 'approver'])
            ->where('document_number', $document->document_number)
            ->orderByDesc('revision_number')
            ->get();

        return view('admin.documents.show', compact('document', 'revisionHistory'));
    }

    // ─── EDIT ────────────────────────────────────────────────────────────
    public function edit(Document $document)
    {
        $this->authorizeDraftEditor($document);

        return view('admin.documents.edit', compact('document'));
    }

    // ─── UPDATE ──────────────────────────────────────────────────────────
    public function update(Request $request, Document $document)
    {
        $this->authorizeDraftEditor($document);

        abort_unless(
            $document->status === 'draft',
            422,
            'Hanya dokumen draft yang dapat diedit.'
        );

        $request->validate([
            'title'            => 'required|string|max:255',
            'document_number'  => 'required|string|max:255',
            'category'         => 'required|in:policy,standard,procedure,legal,form_template,record,emergency',
            'owner_department' => 'nullable|string|max:255',
            'effective_date'   => 'nullable|date',
            'review_date'      => 'nullable|date|after_or_equal:effective_date',
            'description'      => 'nullable|string',
            'change_notes'     => 'nullable|string',
            'file'             => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png|max:51200',
        ]);

        if ($request->hasFile('file')) {
            DocumentVersion::create([
                'document_id'     => $document->id,
                'revision_number' => $document->revision_number,
                'file_url'        => $document->file_url,
                'file_name'       => $document->file_name,
                'file_type'       => $document->file_type,
                'file_size'       => $document->file_size,
                'status'          => $document->status,
                'change_notes'    => $request->change_notes,
                'uploaded_by'     => $document->uploaded_by,
            ]);

            $file = $request->file('file');
            $fileUrl = $this->storage->upload($file, 'documents');

            if (!$fileUrl) {
                return back()->withErrors([
                    'file' => 'Gagal mengupload file ke storage.',
                ])->withInput();
            }

            $document->file_url = $fileUrl;
            $document->file_name = $file->getClientOriginalName();
            $document->file_type = $file->getClientOriginalExtension();
            $document->file_size = $file->getSize();
            $document->status = 'draft';
            $document->approved_by = null;
            $document->approved_at = null;
        }

        $document->title = $request->title;
        $document->document_number = $request->document_number;
        $document->category = $request->category;
        $document->owner_department = $request->owner_department;
        $document->effective_date = $request->effective_date;
        $document->review_date = $request->review_date;
        $document->description = $request->description;
        $document->save();

        AuditLogger::record(
            'documents',
            'update',
            "Update dokumen: {$document->title} (Rev. {$document->revision_number})",
            $document->id
        );

        return redirect()->route('admin.documents.show', $document)
            ->with('success', 'Dokumen draft berhasil diperbarui.');
    }

    // ─── SUBMIT REVIEW ───────────────────────────────────────────────────
    public function submitReview(Document $document)
    {
        $user = Auth::user();

        abort_unless(
            in_array($user->role, ['sys_admin', 'k3_manager', 'k3_officer']),
            403,
            'Anda tidak memiliki hak untuk mengajukan review.'
        );

        abort_unless(
            $document->status === 'draft',
            422,
            'Hanya dokumen draft yang bisa diajukan review.'
        );

        if ($user->role === 'k3_officer') {
            abort_unless(
                $document->uploaded_by === $user->id,
                403,
                'K3 Officer hanya bisa mengajukan dokumen miliknya sendiri.'
            );
        }

        $document->status = 'under_review';
        $document->save();

        AuditLogger::record(
            'documents',
            'submit_review',
            "Dokumen {$document->document_number} diajukan untuk review",
            $document->id
        );

        return back()->with('success', 'Dokumen berhasil diajukan untuk review.');
    }

    // ─── APPROVE ─────────────────────────────────────────────────────────
    public function approve(Document $document)
    {
        $this->authorizeApprover();

        abort_unless(
            $document->status === 'under_review',
            422,
            'Hanya dokumen under review yang dapat diapprove.'
        );

        Document::where('document_number', $document->document_number)
            ->where('status', 'approved')
            ->where('id', '!=', $document->id)
            ->update(['status' => 'obsolete']);

        $document->status = 'approved';
        $document->approved_by = Auth::id();
        $document->approved_at = now();
        $document->save();

        AuditLogger::record(
            'documents',
            'approve',
            "Dokumen {$document->document_number} Rev.{$document->revision_number} disetujui. Revisi lama dijadikan obsolete.",
            $document->id
        );

        return back()->with('success', 'Dokumen berhasil diapprove. Revisi lama otomatis menjadi obsolete.');
    }

    // ─── REJECT ──────────────────────────────────────────────────────────
    public function reject(Request $request, Document $document)
    {
        $this->authorizeApprover();

        abort_unless(
            $document->status === 'under_review',
            422,
            'Hanya dokumen under review yang dapat ditolak.'
        );

        $request->validate([
            'review_note' => 'required|string|max:2000',
        ]);

        $document->status = 'draft';
        $document->review_note = $request->review_note;
        $document->save();

        AuditLogger::record(
            'documents',
            'reject',
            "Dokumen {$document->document_number} dikembalikan ke draft. Alasan: {$request->review_note}",
            $document->id
        );

        return back()->with('success', 'Dokumen dikembalikan ke draft.');
    }

    // ─── REVISE ──────────────────────────────────────────────────────────
    public function revise(Document $document)
    {
        $user = Auth::user();

        abort_unless(
            in_array($user->role, ['sys_admin', 'k3_manager', 'k3_officer']),
            403,
            'Anda tidak memiliki akses untuk membuat revisi.'
        );

        abort_unless(
            $document->status === 'approved',
            422,
            'Hanya dokumen approved yang bisa direvisi.'
        );

        if ($user->role === 'k3_officer') {
            abort_unless(
                $document->uploaded_by === $user->id,
                403,
                'K3 Officer hanya dapat merevisi dokumen miliknya sendiri.'
            );
        }

        $lastRevision = Document::where('document_number', $document->document_number)
            ->max('revision_number');

        $newDocument = Document::create([
            'title'            => $document->title,
            'document_number'  => $document->document_number,
            'category'         => $document->category,
            'owner_department' => $document->owner_department,
            'description'      => $document->description,
            'file_url'         => $document->file_url,
            'file_name'        => $document->file_name,
            'file_type'        => $document->file_type,
            'file_size'        => $document->file_size,
            'effective_date'   => $document->effective_date,
            'review_date'      => $document->review_date,
            'status'           => 'draft',
            'revision_number'  => ($lastRevision ?? 0) + 1,
            'uploaded_by'      => $user->id,
            'approved_by'      => null,
            'approved_at'      => null,
            'review_note'      => null,
        ]);

        AuditLogger::record(
            'documents',
            'revise',
            "Revisi baru dibuat dari {$document->document_number} Rev.{$document->revision_number} → Rev.{$newDocument->revision_number}",
            $newDocument->id
        );

        return redirect()->route('admin.documents.edit', $newDocument)
            ->with('success', "Revisi baru (Rev. {$newDocument->revision_number}) berhasil dibuat. Silakan upload file revisi terbaru.");
    }

    // ─── DESTROY ─────────────────────────────────────────────────────────
    public function destroy(Document $document)
    {
        $this->authorizeApprover();

        $document->load('versions');

        $this->storage->delete($document->file_url);

        foreach ($document->versions as $version) {
            $this->storage->delete($version->file_url);
        }

        AuditLogger::record(
            'documents',
            'delete',
            "Hapus dokumen: {$document->title} ({$document->document_number})",
            $document->id
        );

        $document->delete();

        return redirect()->route('admin.documents.index')
            ->with('success', 'Dokumen berhasil dihapus.');
    }

    // ─── HELPER AUTHORIZATION ────────────────────────────────────────────
    private function authorizeDocumentManager(): void
    {
        abort_unless(
            in_array(Auth::user()->role, ['sys_admin', 'k3_manager', 'k3_officer']),
            403,
            'Anda tidak memiliki akses untuk mengelola dokumen.'
        );
    }

    private function authorizeApprover(): void
    {
        abort_unless(
            in_array(Auth::user()->role, ['sys_admin', 'k3_manager']),
            403,
            'Hanya sys_admin atau k3_manager yang dapat melakukan aksi ini.'
        );
    }

    private function authorizeDraftEditor(Document $document): void
    {
        $user = Auth::user();

        abort_unless(
            in_array($user->role, ['sys_admin', 'k3_manager', 'k3_officer']),
            403,
            'Anda tidak memiliki akses untuk mengedit dokumen.'
        );

        if ($user->role === 'k3_officer') {
            abort_unless(
                $document->uploaded_by === $user->id,
                403,
                'K3 Officer hanya dapat mengedit dokumen miliknya sendiri.'
            );
        }
    }

    private function authorizeViewDocument(Document $document): void
    {
        $user = Auth::user();

        if (in_array($user->role, ['sys_admin', 'k3_manager'])) {
            return;
        }

        if ($user->role === 'k3_officer') {
            $canView = $document->status === 'approved' || $document->uploaded_by === $user->id;
            abort_unless($canView, 403, 'Anda tidak memiliki akses ke dokumen ini.');
            return;
        }

        if ($user->role === 'auditor') {
            $canView = in_array($document->status, ['approved', 'obsolete']);
            abort_unless($canView, 403, 'Anda tidak memiliki akses ke dokumen ini.');
            return;
        }

        abort_unless(
            $document->status === 'approved',
            403,
            'Anda hanya dapat melihat dokumen yang sudah approved.'
        );
    }
}