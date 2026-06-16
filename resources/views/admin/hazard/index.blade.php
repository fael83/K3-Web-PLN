@extends('layouts.admin')
@section('title', 'Manajemen Bahaya')

@php
    $levelClass = [
        'rendah' => 'bg-success-subtle text-success-emphasis',
        'sedang' => 'bg-warning-subtle text-warning-emphasis',
        'tinggi' => 'bg-danger-subtle text-danger-emphasis',
    ];
@endphp

@section('content')
@include('admin.partials._header', [
    'heading'     => 'Identifikasi Bahaya',
    'subheading'  => 'Daftar potensi bahaya beserta tingkat risiko dan pengendaliannya.',
    'actionLabel' => 'Tambah Bahaya',
    'actionUrl'   => route('admin.hazard.create'),
])

<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Nama Bahaya</th>
                    <th class="d-none d-md-table-cell">Kategori</th>
                    <th style="width:100px;">Kemungkinan</th>
                    <th style="width:100px;">Keparahan</th>
                    <th class="text-end" style="width:140px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($items as $item)
                    <tr>
                        <td>
                            <div class="fw-semibold">{{ $item->name }}</div>
                            <div class="text-muted small">{{ $item->location ?: '—' }}</div>
                        </td>
                        <td class="d-none d-md-table-cell">{{ \App\Models\Hazard::CATEGORIES[$item->category] ?? $item->category }}</td>
                        <td><span class="badge {{ $levelClass[$item->likelihood] ?? 'bg-secondary-subtle' }}">{{ \App\Models\Hazard::LEVELS[$item->likelihood] ?? $item->likelihood }}</span></td>
                        <td><span class="badge {{ $levelClass[$item->severity] ?? 'bg-secondary-subtle' }}">{{ \App\Models\Hazard::LEVELS[$item->severity] ?? $item->severity }}</span></td>
                        <td class="text-end">
                            <a href="{{ route('admin.hazard.edit', $item) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil"></i></a>
                            <form action="{{ route('admin.hazard.destroy', $item) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus data bahaya ini?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-center text-muted py-4">Belum ada data bahaya.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-3">{{ $items->links() }}</div>
@endsection
