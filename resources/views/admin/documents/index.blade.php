@extends('layouts.admin')

@section('title', 'Manajemen Dokumen')

@section('content')
<div class="container-fluid">
    @php
        $role = auth()->user()->role ?? '';
        $userId = auth()->id();

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

        $canManage = in_array($role, ['sys_admin', 'k3_manager', 'k3_officer']);
        $canApprove = in_array($role, ['sys_admin', 'k3_manager']);
    @endphp

    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <h4 class="fw-bold mb-1">📄 Manajemen Dokumen K3</h4>
            <div class="text-muted small">
                Kelola dokumen, revisi, status persetujuan, dan masa tinjau dokumen.
            </div>
        </div>

        @if($canManage)
            <a href="{{ route('admin.documents.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle me-1"></i> Tambah Dokumen
            </a>
        @endif
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($expiringSoon > 0)
        <div class="alert alert-warning d-flex align-items-center mb-4" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            Ada <strong class="mx-1">{{ $expiringSoon }}</strong> dokumen approved yang mendekati tanggal review.
        </div>
    @endif

    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.documents.index') }}">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Cari Dokumen</label>
                        <input
                            type="text"
                            name="search"
                            class="form-control"
                            value="{{ request('search') }}"
                            placeholder="Judul atau nomor dokumen">
                    </div>

                    <div class="col-md-2">
                        <label class="form-label fw-semibold">Status</label>
                        <select name="status" class="form-select">
                            <option value="">Semua Status</option>
                            @foreach($statusLabels as $value => $label)
                                <option value="{{ $value }}" {{ request('status') === $value ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Kategori</label>
                        <select name="category" class="form-select">
                            <option value="">Semua Kategori</option>
                            @foreach($categoryLabels as $value => $label)
                                <option value="{{ $value }}" {{ request('category') === $value ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Departemen</label>
                        <input
                            type="text"
                            name="department"
                            class="form-control"
                            value="{{ request('department') }}"
                            placeholder="Contoh: K3 / HR / Teknik">
                    </div>

                    <div class="col-12 d-flex flex-wrap gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-search me-1"></i> Terapkan Filter
                        </button>

                        <a href="{{ route('admin.documents.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-clockwise me-1"></i> Reset
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th width="60">#</th>
                            <th>Judul Dokumen</th>
                            <th>Nomor Dokumen</th>
                            <th>Kategori</th>
                            <th>Versi</th>
                            <th>Status</th>
                            <th>Diupload Oleh</th>
                            <th>Tanggal</th>
                            <th class="text-center" width="190">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($documents as $doc)
                            @php
                                $isOwner = (int) ($doc->uploaded_by ?? 0) === (int) $userId;
                                $canEdit = $doc->status === 'draft'
                                    && (
                                        in_array($role, ['sys_admin', 'k3_manager']) ||
                                        ($role === 'k3_officer' && $isOwner)
                                    );
                            @endphp

                            <tr>
                                <td>{{ $documents->firstItem() + $loop->index }}</td>
                                <td>
                                    <div class="fw-semibold">{{ $doc->title }}</div>

                                    @if($doc->owner_department)
                                        <div class="small text-muted">{{ $doc->owner_department }}</div>
                                    @endif

                                    @if(!empty($doc->review_note) && $doc->status === 'draft')
                                        <div class="small text-warning mt-1">
                                            <i class="bi bi-exclamation-circle me-1"></i>
                                            Ada catatan review
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <span class="small fw-semibold">{{ $doc->document_number }}</span>
                                </td>
                                <td>
                                    <span class="badge rounded-pill {{ $categoryClasses[$doc->category] ?? 'bg-secondary-subtle text-secondary' }}">
                                        {{ $categoryLabels[$doc->category] ?? ucfirst(str_replace('_', ' ', $doc->category)) }}
                                    </span>
                                </td>
                                <td>
                                    <span class="fw-semibold">Rev. {{ $doc->revision_number ?? 1 }}</span>
                                </td>
                                <td>
                                    <span class="badge {{ $statusClasses[$doc->status] ?? 'bg-dark' }}">
                                        {{ $statusLabels[$doc->status] ?? ucfirst(str_replace('_', ' ', $doc->status)) }}
                                    </span>
                                </td>
                                <td>{{ $doc->uploader->name ?? '-' }}</td>
                                <td>
                                    <div>{{ optional($doc->created_at)->format('d/m/Y') }}</div>
                                    <div class="small text-muted">{{ optional($doc->created_at)->format('H:i') }}</div>
                                </td>
                                <td class="text-center">
                                    <div class="d-inline-flex gap-1 flex-wrap justify-content-center">
                                        <a href="{{ route('admin.documents.show', $doc) }}"
                                           class="btn btn-sm btn-info text-white"
                                           title="Lihat">
                                            <i class="bi bi-eye"></i>
                                        </a>

                                        @if($canEdit)
                                            <a href="{{ route('admin.documents.edit', $doc) }}"
                                               class="btn btn-sm btn-warning"
                                               title="Edit Draft">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                        @endif

                                        @if($canApprove)
                                            <form action="{{ route('admin.documents.destroy', $doc) }}"
                                                  method="POST"
                                                  class="d-inline"
                                                  onsubmit="return confirm('Yakin hapus dokumen ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" title="Hapus">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center text-muted py-5">
                                    <i class="bi bi-folder2-open d-block fs-2 mb-2"></i>
                                    Belum ada dokumen yang cocok dengan filter saat ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @if($documents->count())
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mt-3">
            <div class="small text-muted">
                Menampilkan {{ $documents->firstItem() }}–{{ $documents->lastItem() }}
                dari total {{ $documents->total() }} dokumen.
            </div>

            <div>
                {{ $documents->links() }}
            </div>
        </div>
    @endif
</div>
@endsection