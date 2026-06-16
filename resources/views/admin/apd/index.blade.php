@extends('layouts.admin')
@section('title', 'Manajemen APD')

@section('content')
@include('admin.partials._header', [
    'heading'     => 'Alat Pelindung Diri (APD)',
    'subheading'  => 'Kelola daftar APD beserta gambar dan standar acuan.',
    'actionLabel' => 'Tambah APD',
    'actionUrl'   => route('admin.apd.create'),
])

<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th style="width:64px;">Gambar</th>
                    <th>Nama APD</th>
                    <th class="d-none d-md-table-cell">Area Penggunaan</th>
                    <th class="d-none d-lg-table-cell">Standar</th>
                    <th style="width:90px;">Status</th>
                    <th class="text-end" style="width:140px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($items as $item)
                    <tr>
                        <td>
                            @if ($item->image_url)
                                <img src="{{ $item->image_url }}" alt="{{ $item->name }}" class="rounded" style="width:48px;height:48px;object-fit:cover;">
                            @else
                                <span class="card-icon" style="width:48px;height:48px;font-size:1rem;"><i class="bi bi-shield-shaded"></i></span>
                            @endif
                        </td>
                        <td>
                            <div class="fw-semibold">{{ $item->name }}</div>
                            <div class="text-muted small d-md-none">{{ $item->usage_area }}</div>
                        </td>
                        <td class="d-none d-md-table-cell">{{ $item->usage_area ?: '—' }}</td>
                        <td class="d-none d-lg-table-cell">{{ $item->standard ?: '—' }}</td>
                        <td>
                            @if ($item->is_active)
                                <span class="badge bg-success-subtle text-success-emphasis">Aktif</span>
                            @else
                                <span class="badge bg-secondary-subtle text-secondary-emphasis">Nonaktif</span>
                            @endif
                        </td>
                        <td class="text-end">
                            <a href="{{ route('admin.apd.edit', $item) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil"></i></a>
                            <form action="{{ route('admin.apd.destroy', $item) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus APD ini?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center text-muted py-4">Belum ada data APD.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-3">{{ $items->links() }}</div>
@endsection
