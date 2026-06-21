@extends('layouts.admin')

@section('title', 'Detail Dokumen')

@section('content')
<div class="container-fluid">

    <div class="d-flex align-items-center mb-4">
        <a href="{{ route('admin.documents.index') }}" class="btn btn-outline-secondary me-3">
            <i class="bi bi-arrow-left"></i>
        </a>
        <h4 class="fw-bold mb-0">Detail Dokumen</h4>
    </div>

    <div class="row g-4">

        {{-- Info Dokumen --}}
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header fw-semibold bg-light">Informasi Dokumen</div>
                <div class="card-body">
                    <table class="table table-borderless mb-0">
                        <tr>
                            <th width="30%" class="text-muted">Judul</th>
                            <td>{{ $document->title }}</td>
                        </tr>
                        <tr>
                            <th class="text-muted">Kategori</th>
                            <td><span class="badge bg-secondary">{{ $document->category }}</span></td>
                        </tr>
                        <tr>
                            <th class="text-muted">Versi</th>
                            <td>{{ $document->version ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th class="text-muted">Status</th>
                            <td>
                                @if($document->status === 'draft')
                                    <span class="badge bg-secondary">Draft</span>
                                @elseif($document->status === 'under_review')
                                    <span class="badge bg-warning text-dark">Under Review</span>
                                @elseif($document->status === 'pending')
                                    <span class="badge bg-warning text-dark">Pending</span>
                                @elseif($document->status === 'approved')
                                    <span class="badge bg-success">Approved</span>
                                @elseif($document->status === 'rejected')
                                    <span class="badge bg-danger">Rejected</span>
                                @else
                                    <span class="badge bg-dark">{{ ucfirst(str_replace('_', ' ', $document->status)) }}</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th class="text-muted">Deskripsi</th>
                            <td>{{ $document->description ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th class="text-muted">Diupload Oleh</th>
                            <td>{{ $document->uploader->name ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th class="text-muted">Tanggal Upload</th>
                            <td>{{ $document->created_at->format('d F Y, H:i') }}</td>
                        </tr>
                        @if($document->approved_at)
                        <tr>
                            <th class="text-muted">Disetujui Pada</th>
                            <td>{{ \Carbon\Carbon::parse($document->approved_at)->format('d F Y, H:i') }}</td>
                        </tr>
                        @endif
                    </table>
                </div>
            </div>
        </div>

        {{-- Aksi --}}
        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-header fw-semibold bg-light">Aksi</div>
                <div class="card-body d-grid gap-2">

                    <a href="{{ $document->file_url }}" target="_blank" class="btn btn-primary">
                        <i class="bi bi-download me-1"></i> Download / Lihat File
                    </a>

                    <a href="{{ route('admin.documents.edit', $document) }}" class="btn btn-warning">
                        <i class="bi bi-pencil me-1"></i> Edit Dokumen
                    </a>

                    @if($document->status === 'draft')
                    <form action="{{ route('admin.documents.approve', $document) }}" method="POST">
                        @csrf
                        <input type="hidden" name="action" value="submit_review">
                        <button type="submit" class="btn btn-info text-white w-100">
                            <i class="bi bi-send me-1"></i> Ajukan Review
                        </button>
                    </form>
                    @endif

                    @if($document->status === 'under_review')
                    <form action="{{ route('admin.documents.approve', $document) }}" method="POST">
                        @csrf
                        <input type="hidden" name="action" value="approve">
                        <button type="submit" class="btn btn-success w-100 mb-2">
                            <i class="bi bi-check-circle me-1"></i> Approve Dokumen
                        </button>
                    </form>

                    <form action="{{ route('admin.documents.approve', $document) }}" method="POST">
                        @csrf
                        <input type="hidden" name="action" value="reject">
                        <button type="submit" class="btn btn-outline-danger w-100">
                            <i class="bi bi-x-circle me-1"></i> Tolak / Kembalikan ke Draft
                        </button>
                    </form>
                    @endif

                    <form action="{{ route('admin.documents.destroy', $document) }}" method="POST"
                          onsubmit="return confirm('Yakin hapus dokumen ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger w-100">
                            <i class="bi bi-trash me-1"></i> Hapus Dokumen
                        </button>
                    </form>

                </div>
            </div>
        </div>

    </div>

</div>
@endsection