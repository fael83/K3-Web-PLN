@extends('layouts.admin')
@section('title', 'Manajemen SOP')

@section('content')
@include('admin.partials._header', [
    'heading'     => 'SOP Keselamatan Kerja',
    'subheading'  => 'Langkah-langkah prosedur kerja aman per sektor.',
    'actionLabel' => 'Tambah Langkah',
    'actionUrl'   => route('admin.sop.create'),
])

<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th style="width:64px;">Urutan</th>
                    <th>Judul Langkah</th>
                    <th class="d-none d-md-table-cell">Sektor</th>
                    <th class="text-end" style="width:140px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($items as $item)
                    <tr>
                        <td><span class="badge bg-primary-subtle text-primary-emphasis">{{ $item->step_order }}</span></td>
                        <td>
                            <div class="fw-semibold">{{ $item->title }}</div>
                            <div class="text-muted small text-truncate" style="max-width:480px;">{{ $item->description }}</div>
                        </td>
                        <td class="d-none d-md-table-cell">
                            <span class="badge bg-info-subtle text-info-emphasis">{{ \App\Models\SopStep::SECTORS[$item->sector] ?? $item->sector }}</span>
                        </td>
                        <td class="text-end">
                            <a href="{{ route('admin.sop.edit', $item) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil"></i></a>
                            <form action="{{ route('admin.sop.destroy', $item) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus langkah SOP ini?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="text-center text-muted py-4">Belum ada langkah SOP.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-3">{{ $items->links() }}</div>
@endsection
