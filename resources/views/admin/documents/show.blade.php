@extends('layouts.admin')

@section('title', 'Detail Dokumen')

@section('content')
<div class="container-fluid">
    @php
        $role = auth()->user()->role ?? '';

        $categoryLabels = [
            'policy'        => 'Policy',
            'standard'      => 'Standard',
            'procedure'     => 'Procedure / SOP',
            'legal'         => 'Legal',
            'form_template' => 'Form Template',
            'record'        => 'Record',
            'emergency'     => 'Emergency',
        ];

        $categoryClasses = [
            'policy'        => 'bg-primary-subtle text-primary',
            'standard'      => 'bg-secondary-subtle text-secondary',
            'procedure'     => 'bg-info-subtle text-info',
            'legal'         => 'bg-dark-subtle text-dark',
            'form_template' => 'bg-warning-subtle text-warning',
            'record'        => 'bg-success-subtle text-success',
            'emergency'     => 'bg-danger-subtle text-danger',
        ];

        $statusLabels = [
            'draft'        => 'Draft',
            'under_review' => 'Under Review',
            'approved'     => 'Approved',
            'obsolete'     => 'Obsolete',
        ];

        $statusClasses = [
            'draft'        => 'bg-secondary',
            'under_review' => 'bg-warning text-dark',
            'approved'     => 'bg-success',
            'obsolete'     => 'bg-danger',
        ];

        $fileType = strtolower($document->file_type ?? '');
        $isPdf = $fileType === 'pdf';
        $isImage = in_array($fileType, ['jpg', 'jpeg', 'png', 'gif', 'webp']);

        $canManage = in_array($role, ['sys_admin', 'k3_manager', 'k3_officer']);
        $canApprove = in_array($role, ['sys_admin', 'k3_manager']);
        $isOwner = (int) ($document->uploaded_by ?? 0) === (int) (auth()->id() ?? 0);

        $canSeeRevisionHistory = in_array($role, [
            'sys_admin',
            'k3_manager',
            'k3_officer',
            'auditor',
            'department_head',
        ]);
    @endphp

    <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 mb-4">
        <div class="d-flex align-items-center">
            <a href="{{ route('admin.documents.index') }}" class="btn btn-outline-secondary me-3">
                <i class="bi bi-arrow-left"></i>
            </a>
            <div>
                <h4 class="fw-bold mb-1">Detail Dokumen</h4>
                <div class="text-muted small">
                    @if($canSeeRevisionHistory)
                        Lihat metadata, file aktif, status approval, dan riwayat revisi dokumen.
                    @else
                        Lihat metadata dan file dokumen yang berlaku.
                    @endif
                </div>
            </div>
        </div>

        <div class="d-flex flex-wrap gap-2">
            <span class="badge rounded-pill {{ $categoryClasses[$document->category] ?? 'bg-secondary-subtle text-secondary' }}">
                {{ $categoryLabels[$document->category] ?? ucfirst(str_replace('_', ' ', $document->category)) }}
            </span>

            <span class="badge {{ $statusClasses[$document->status] ?? 'bg-dark' }}">
                {{ $statusLabels[$document->status] ?? ucfirst(str_replace('_', ' ', $document->status)) }}
            </span>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <div class="fw-semibold mb-1">Terjadi kesalahan:</div>
            <ul class="mb-0 ps-3">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header fw-semibold bg-light">Informasi Dokumen</div>
                <div class="card-body">
                    <table class="table table-borderless align-middle mb-0">
                        <tr>
                            <th width="32%" class="text-muted">Judul</th>
                            <td class="fw-semibold">{{ $document->title }}</td>
                        </tr>
                        <tr>
                            <th class="text-muted">Nomor Dokumen</th>
                            <td>{{ $document->document_number }}</td>
                        </tr>
                        <tr>
                            <th class="text-muted">Kategori</th>
                            <td>
                                <span class="badge rounded-pill {{ $categoryClasses[$document->category] ?? 'bg-secondary-subtle text-secondary' }}">
                                    {{ $categoryLabels[$document->category] ?? ucfirst(str_replace('_', ' ', $document->category)) }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <th class="text-muted">Versi Aktif</th>
                            <td>Rev. {{ $document->revision_number ?? 1 }}</td>
                        </tr>
                        <tr>
                            <th class="text-muted">Status</th>
                            <td>
                                <span class="badge {{ $statusClasses[$document->status] ?? 'bg-dark' }}">
                                    {{ $statusLabels[$document->status] ?? ucfirst(str_replace('_', ' ', $document->status)) }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <th class="text-muted">Departemen Pemilik</th>
                            <td>{{ $document->owner_department ?: '-' }}</td>
                        </tr>
                        <tr>
                            <th class="text-muted">Nama File</th>
                            <td>{{ $document->file_name ?: '-' }}</td>
                        </tr>
                        <tr>
                            <th class="text-muted">Tipe File</th>
                            <td>{{ strtoupper($document->file_type ?? '-') }}</td>
                        </tr>
                        <tr>
                            <th class="text-muted">Ukuran File</th>
                            <td>
                                @if($document->file_size)
                                    {{ number_format($document->file_size / 1024, 2) }} KB
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th class="text-muted">Tanggal Efektif</th>
                            <td>{{ optional($document->effective_date)->format('d F Y') ?: '-' }}</td>
                        </tr>
                        <tr>
                            <th class="text-muted">Tanggal Review</th>
                            <td>{{ optional($document->review_date)->format('d F Y') ?: '-' }}</td>
                        </tr>
                        <tr>
                            <th class="text-muted">Deskripsi</th>
                            <td>{{ $document->description ?: '-' }}</td>
                        </tr>
                        <tr>
                            <th class="text-muted">Diupload Oleh</th>
                            <td>{{ $document->uploader->name ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th class="text-muted">Tanggal Upload</th>
                            <td>{{ optional($document->created_at)->format('d F Y, H:i') ?: '-' }}</td>
                        </tr>

                        @if($canSeeRevisionHistory)
                            <tr>
                                <th class="text-muted">Disetujui Oleh</th>
                                <td>{{ $document->approver->name ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th class="text-muted">Disetujui Pada</th>
                                <td>{{ optional($document->approved_at)->format('d F Y, H:i') ?: '-' }}</td>
                            </tr>

                            @if(!empty($document->review_note))
                                <tr>
                                    <th class="text-muted">Catatan Review</th>
                                    <td>
                                        <div class="alert alert-warning mb-0 py-2 px-3">
                                            {{ $document->review_note }}
                                        </div>
                                    </td>
                                </tr>
                            @endif
                        @endif
                    </table>
                </div>
            </div>

            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header fw-semibold bg-light">Preview Dokumen</div>
                <div class="card-body">
                    @if($isPdf)
                        <iframe
                            src="{{ $document->file_url }}"
                            width="100%"
                            height="700"
                            style="border:1px solid #dee2e6; border-radius: .5rem;">
                        </iframe>
                    @elseif($isImage)
                        <div class="text-center">
                            <img
                                src="{{ $document->file_url }}"
                                alt="{{ $document->title }}"
                                class="img-fluid rounded border">
                        </div>
                    @else
                        <div class="alert alert-info mb-0">
                            Preview langsung belum tersedia untuk tipe file ini. Gunakan tombol download / buka file.
                        </div>
                    @endif
                </div>
            </div>

            @if($canSeeRevisionHistory)
                <div class="card shadow-sm border-0">
                    <div class="card-header fw-semibold bg-light">Riwayat Revisi</div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Revisi</th>
                                        <th>Nama File</th>
                                        <th>Status Saat Itu</th>
                                        <th>Uploader</th>
                                        <th>Catatan Perubahan</th>
                                        <th>Tanggal</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($revisionHistory as $history)
                                        <tr @class(['table-active' => $history->id === $document->id])>
                                            <td>Rev. {{ $history->revision_number }}</td>
                                            <td>
                                                {{ $history->file_name }}
                                                @if($history->id === $document->id)
                                                    <span class="badge bg-primary ms-1">Aktif dibuka</span>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="badge {{ $statusClasses[$history->status] ?? 'bg-dark' }}">
                                                    {{ $statusLabels[$history->status] ?? ucfirst(str_replace('_', ' ', $history->status)) }}
                                                </span>
                                            </td>
                                            <td>{{ $history->uploader->name ?? '-' }}</td>
                                            <td>{{ $history->description ?: '-' }}</td>
                                            <td>{{ optional($history->created_at)->format('d/m/Y H:i') ?: '-' }}</td>
                                            <td class="text-nowrap">
                                                <a href="{{ route('admin.documents.show', $history) }}" class="btn btn-sm btn-outline-secondary">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                                <a href="{{ $history->file_url }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                                    <i class="bi bi-download"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center text-muted py-4">
                                                Belum ada riwayat revisi. Dokumen ini masih memakai versi awal.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <div class="col-lg-4">
            <div class="card shadow-sm border-0">
                <div class="card-header fw-semibold bg-light">Aksi</div>
                <div class="card-body d-grid gap-2">
                    <a href="{{ $document->file_url }}" target="_blank" class="btn btn-primary">
                        <i class="bi bi-download me-1"></i> Download / Lihat File
                    </a>

                    @if($canManage && $document->status === 'draft' && ($role !== 'k3_officer' || $isOwner))
                        <a href="{{ route('admin.documents.edit', $document) }}" class="btn btn-warning">
                            <i class="bi bi-pencil me-1"></i> Edit Dokumen
                        </a>
                    @endif

                    @if($canManage && $document->status === 'approved' && ($role !== 'k3_officer' || $isOwner))
                        <form action="{{ route('admin.documents.revise', $document) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-outline-warning w-100"
                                onclick="return confirm('Buat revisi baru dari dokumen ini?')">
                                <i class="bi bi-arrow-repeat me-1"></i> Buat Revisi Baru
                            </button>
                        </form>
                    @endif

                    @if($document->status === 'draft' && $canManage && ($role !== 'k3_officer' || $isOwner))
                        <form action="{{ route('admin.documents.submitReview', $document) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-info text-white w-100">
                                <i class="bi bi-send me-1"></i> Ajukan Review
                            </button>
                        </form>
                    @endif

                    @if($document->status === 'under_review' && $canApprove)
                        <form action="{{ route('admin.documents.approve', $document) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-success w-100">
                                <i class="bi bi-check-circle me-1"></i> Approve Dokumen
                            </button>
                        </form>

                        <form action="{{ route('admin.documents.reject', $document) }}" method="POST">
                            @csrf
                            <div class="mb-2">
                                <label for="review_note" class="form-label fw-semibold">Alasan Pengembalian</label>
                                <textarea
                                    name="review_note"
                                    id="review_note"
                                    rows="3"
                                    class="form-control @error('review_note') is-invalid @enderror"
                                    placeholder="Tuliskan alasan dokumen dikembalikan ke draft...">{{ old('review_note') }}</textarea>
                                @error('review_note')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <button type="submit" class="btn btn-outline-danger w-100">
                                <i class="bi bi-x-circle me-1"></i> Tolak / Kembalikan ke Draft
                            </button>
                        </form>
                    @endif

                    @if($canApprove)
                        <form action="{{ route('admin.documents.destroy', $document) }}" method="POST"
                              onsubmit="return confirm('Yakin hapus dokumen ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger w-100">
                                <i class="bi bi-trash me-1"></i> Hapus Dokumen
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection