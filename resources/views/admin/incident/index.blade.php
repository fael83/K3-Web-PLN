@extends('layouts.admin')
@section('title', 'Manajemen Insiden')

@php
    $statusClass = [
        'open'        => 'bg-danger-subtle text-danger-emphasis',
        'investigasi' => 'bg-warning-subtle text-warning-emphasis',
        'selesai'     => 'bg-success-subtle text-success-emphasis',
    ];
@endphp

@section('content')
@include('admin.partials._header', [
    'heading'     => 'Pelaporan Insiden',
    'subheading'  => 'Catatan insiden, near miss, dan tindak lanjut korektif.',
    'actionLabel' => 'Catat Insiden',
    'actionUrl'   => route('admin.incident.create'),
])

<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Insiden</th>
                    <th class="d-none d-md-table-cell">Jenis</th>
                    <th class="d-none d-lg-table-cell" style="width:120px;">Tanggal</th>
                    <th style="width:110px;">Status</th>
                    <th class="text-end" style="width:140px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($items as $item)
                    <tr>
                        <td>
                            <div class="fw-semibold">{{ $item->title }}</div>
                            <div class="text-muted small">{{ $item->location ?: '—' }}</div>
                        </td>
                        <td class="d-none d-md-table-cell">{{ \App\Models\Incident::TYPES[$item->incident_type] ?? $item->incident_type }}</td>
                        <td class="d-none d-lg-table-cell">{{ optional($item->incident_date)->format('d M Y') ?: '—' }}</td>
                        <td><span class="badge {{ $statusClass[$item->status] ?? 'bg-secondary-subtle' }}">{{ \App\Models\Incident::STATUSES[$item->status] ?? $item->status }}</span></td>
                        <td class="text-end">
                            <a href="{{ route('admin.incident.edit', $item) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil"></i></a>
                            <form action="{{ route('admin.incident.destroy', $item) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus data insiden ini?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-center text-muted py-4">Belum ada data insiden.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-3">{{ $items->links() }}</div>
@endsection
