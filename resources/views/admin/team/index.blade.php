@extends('layouts.admin')
@section('title', 'Manajemen Tim K3')

@section('content')
@include('admin.partials._header', [
    'heading'     => 'Struktur Tim K3 (P2K3)',
    'subheading'  => 'Susunan organisasi dan tanggung jawab tim K3.',
    'actionLabel' => 'Tambah Anggota',
    'actionUrl'   => route('admin.team.create'),
])

<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th style="width:64px;">No.</th>
                    <th>Jabatan</th>
                    <th class="d-none d-md-table-cell">Nama</th>
                    <th class="d-none d-lg-table-cell" style="width:140px;">Level</th>
                    <th class="text-end" style="width:140px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($items as $item)
                    <tr>
                        <td>{{ $item->sort_order }}</td>
                        <td>
                            <div class="fw-semibold">{{ $item->position }}</div>
                            <div class="text-muted small text-truncate" style="max-width:420px;">{{ $item->responsibility }}</div>
                        </td>
                        <td class="d-none d-md-table-cell">{{ $item->name ?: '—' }}</td>
                        <td class="d-none d-lg-table-cell">
                            <span class="badge bg-primary-subtle text-primary-emphasis">{{ \App\Models\TeamMember::LEVELS[$item->level] ?? $item->level }}</span>
                        </td>
                        <td class="text-end">
                            <a href="{{ route('admin.team.edit', $item) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil"></i></a>
                            <form action="{{ route('admin.team.destroy', $item) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus anggota tim ini?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-center text-muted py-4">Belum ada anggota tim.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-3">{{ $items->links() }}</div>
@endsection
