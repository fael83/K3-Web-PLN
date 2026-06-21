@extends('layouts.admin')

@section('title', 'Manajemen Dokumen')

@section('content')
<div class="container-fluid">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold mb-0">📄 Manajemen Dokumen K3</h4>
        @can('role:sys_admin,k3_manager')
        <a href="{{ route('admin.documents.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle me-1"></i> Tambah Dokumen
        </a>
        @endcan
    </div>

    {{-- Alert --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Tabel --}}
    <div class="card shadow-sm">
        <div class="card-body p-0">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Judul Dokumen</th>
                        <th>Kategori</th>
                        <th>Versi</th>
                        <th>Status</th>
                        <th>Diupload Oleh</th>
                        <th>Tanggal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($documents as $doc)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $doc->title }}</td>
                        <td><span class="badge bg-secondary">{{ $doc->category }}</span></td>
                        <td>{{ $doc->version ?? '-' }}</td>
                        <td>
                            @if($doc->status === 'draft')
                                <span class="badge bg-secondary">Draft</span>
                            @elseif($doc->status === 'under_review')
                                <span class="badge bg-warning text-dark">Under Review</span>
                            @elseif($doc->status === 'pending')
                                <span class="badge bg-warning text-dark">Pending</span>
                            @elseif($doc->status === 'approved')
                                <span class="badge bg-success">Approved</span>
                            @elseif($doc->status === 'rejected')
                                <span class="badge bg-danger">Rejected</span>
                            @else
                                <span class="badge bg-dark">{{ ucfirst(str_replace('_', ' ', $doc->status)) }}</span>
                            @endif
                        </td>
                        <td>{{ $doc->uploader->name ?? '-' }}</td>
                        <td>{{ $doc->created_at->format('d/m/Y') }}</td>
                        <td>
                            <a href="{{ route('admin.documents.show', $doc) }}"
                               class="btn btn-sm btn-info text-white">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="{{ route('admin.documents.edit', $doc) }}"
                               class="btn btn-sm btn-warning">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="{{ route('admin.documents.destroy', $doc) }}"
                                  method="POST" class="d-inline"
                                  onsubmit="return confirm('Yakin hapus dokumen ini?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted py-4">
                            Belum ada dokumen. <a href="{{ route('admin.documents.create') }}">Tambah sekarang</a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Pagination --}}
    <div class="mt-3">
        {{ $documents->links() }}
    </div>

</div>
@endsection