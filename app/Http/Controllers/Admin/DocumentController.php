<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Helpers\AuditLogger;
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

    // ─── INDEX — list semua dokumen + search & filter ───────────────────
    public function index(Request $request)
    {
        $query = Document::with(['uploader', 'approver']);

        // Search by title atau document_number
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'ilike', "%{$search}%")
                  ->orWhere('document_number', 'ilike', "%{$search}%");
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by category
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        // Filter by department
        if ($request->filled('department')) {
            $query->where('owner_department', $request->department);
        }

        $documents = $query->orderByDesc('created_at')->paginate(15)->withQueryString();

        // Alert dokumen expired < 30 hari
        $expiringSoon = Document::expiringSoon()->count();

        return view('admin.documents.index', compact('documents', 'expiringSoon'));
    }

    // ─── CREATE ──────────────────────────────────────────────────────────
    public function create()
    {
        return view('admin.documents.create');
    }

    // ─── STORE — upload dokumen baru ─────────────────────────────────────
    public function store(Request $request)
    {
        $request->validate([
            'title'            => 'required|string|max:255',
            'document_number'  => 'required|string|unique:documents,document_number',
            'category'         => 'required|in:policy,standard,procedure,legal,form_template,record,emergency',
            'owner_department' => 'nullable|string|max:255',
            'effective_date'   => 'nullable|date',
            'review_date'      => 'nullable|date|after_or_equal:effective_date',
            'description'      => 'nullable|string',
            'file'             => 'required|file|mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png|max:51200',
        ]);

        // Upload file ke Supabase Storage
        $file = $request->file('file');
        $fileUrl = $this->storage->upload($file, 'documents');

        if (!$fileUrl) {
            return back()->withErrors([
                'file' => 'Gagal mengupload file ke storage.'
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
            "Upload dokumen: {$document->title} ({$document->document_number})"
        );

        return redirect()->route('admin.documents.show', $document)
                         ->with('success', 'Dokumen berhasil diupload.');
    }

    // ─── SHOW — detail dokumen + riwayat versi ───────────────────────────
    public function show(Document $document)
    {
        $document->load(['uploader', 'approver', 'versions.uploader']);

        return view('admin.documents.show', compact('document'));
    }

    // ─── EDIT ────────────────────────────────────────────────────────────
    public function edit(Document $document)
    {
        return view('admin.documents.edit', compact('document'));
    }

    // ─── UPDATE — upload revisi baru ─────────────────────────────────────
    public function update(Request $request, Document $document)
    {
        $request->validate([
            'title'            => 'required|string|max:255',
            'document_number'  => 'required|string|unique:documents,document_number,' . $document->id,
            'category'         => 'required|in:policy,standard,procedure,legal,form_template,record,emergency',
            'owner_department' => 'nullable|string|max:255',
            'effective_date'   => 'nullable|date',
            'review_date'      => 'nullable|date|after_or_equal:effective_date',
            'description'      => 'nullable|string',
            'change_notes'     => 'nullable|string',
            'file'             => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png|max:51200',
        ]);

        // Jika ada file baru — simpan versi lama dulu
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

            // Upload file baru
            $file = $request->file('file');
            $fileUrl = $this->storage->upload($file, 'documents');

            if (!$fileUrl) {
                return back()->withErrors([
                    'file' => 'Gagal mengupload file ke storage.'
                ])->withInput();
            }

            $document->file_url = $fileUrl;
            $document->file_name = $file->getClientOriginalName();
            $document->file_type = $file->getClientOriginalExtension();
            $document->file_size = $file->getSize();
            $document->revision_number = $document->revision_number + 1;
            $document->status = 'draft';
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
            "Update dokumen: {$document->title} (Rev. {$document->revision_number})"
        );

        return redirect()->route('admin.documents.show', $document)
                         ->with('success', 'Dokumen berhasil diperbarui.');
    }

    // ─── APPROVE — workflow Draft → Review → Approved ────────────────────
    public function approve(Request $request, Document $document)
    {
        $request->validate([
            'action' => 'required|in:submit_review,approve,reject',
        ]);

        $action = $request->action;

        if ($action === 'submit_review' && $document->status === 'draft') {
            $document->status = 'under_review';
            $message = 'Dokumen berhasil diajukan untuk review.';
        } elseif ($action === 'approve' && $document->status === 'under_review') {
            $document->status = 'approved';
            $document->approved_by = Auth::id();
            $document->approved_at = now();
            $message = 'Dokumen berhasil diapprove.';
        } elseif ($action === 'reject' && $document->status === 'under_review') {
            $document->status = 'draft';
            $message = 'Dokumen dikembalikan ke draft.';
        } else {
            return back()->withErrors([
                'action' => 'Aksi tidak valid untuk status dokumen ini.'
            ]);
        }

        $document->save();

        AuditLogger::record(
            'documents',
            'approve',
            "Status dokumen {$document->document_number} diubah ke: {$document->status}"
        );

        return back()->with('success', $message);
    }

    // ─── DESTROY — hapus dokumen ─────────────────────────────────────────
    public function destroy(Document $document)
    {
        $this->storage->delete($document->file_url);

        foreach ($document->versions as $version) {
            $this->storage->delete($version->file_url);
        }

        AuditLogger::record(
            'documents',
            'delete',
            "Hapus dokumen: {$document->title} ({$document->document_number})"
        );

        $document->delete();

        return redirect()->route('admin.documents.index')
                         ->with('success', 'Dokumen berhasil dihapus.');
    }
}