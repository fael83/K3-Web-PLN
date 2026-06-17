@extends('layouts.admin')
@section('title', 'Manajemen Tim K3')

@section('content')
@include('admin.partials._header', [
    'heading'     => 'Struktur Tim K3 (P2K3)',
    'subheading'  => 'Susunan organisasi dan tanggung jawab tim K3.',
    'actionLabel' => 'Tambah Anggota',
    'actionUrl'   => route('admin.team.create'),
])

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th style="width:56px;">No.</th>
                    <th>Jabatan</th>
                    <th class="d-none d-md-table-cell">Nama</th>
                    <th class="d-none d-lg-table-cell">Tanggung Jawab</th>
                    <th class="text-end" style="width:130px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($items as $item)
                <tr>
                    <td class="text-muted small">{{ $item->sort_order }}</td>
                    <td>
                        <span class="fw-semibold">{{ $item->jabatan }}</span>
                        @if($item->status === 'inactive')
                            <span class="badge bg-secondary ms-1">Nonaktif</span>
                        @endif
                    </td>
                    <td class="d-none d-md-table-cell">{{ $item->nama ?: '—' }}</td>
                    <td class="d-none d-lg-table-cell text-muted small" style="max-width:300px;">
                        <span class="text-truncate d-block" style="max-width:280px;">{{ $item->responsibility ?: '—' }}</span>
                    </td>
                    <td class="text-end">
                        <a href="{{ route('admin.team.edit', $item->id) }}" class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <form action="{{ route('admin.team.destroy', $item->id) }}" method="POST"
                              class="d-inline" onsubmit="return confirm('Hapus anggota ini?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center text-muted py-5">
                        <i class="bi bi-people d-block mb-2" style="font-size:2rem; opacity:.3;"></i>
                        Belum ada anggota tim. <a href="{{ route('admin.team.create') }}">Tambah sekarang</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-3">{{ $items->links() }}</div>
@endsection