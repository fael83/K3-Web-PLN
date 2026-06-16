@extends('layouts.admin')
@section('title', 'Manajemen Program Kesehatan')

@section('content')
@include('admin.partials._header', [
    'heading'     => 'Program Kesehatan Kerja',
    'subheading'  => 'Daftar program kesehatan kerja dan kesejahteraan pegawai.',
    'actionLabel' => 'Tambah Program',
    'actionUrl'   => route('admin.health.create'),
])

<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th style="width:64px;">No.</th>
                    <th>Nama Program</th>
                    <th class="d-none d-md-table-cell">Deskripsi</th>
                    <th class="text-end" style="width:140px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($items as $item)
                    <tr>
                        <td>{{ $item->sort_order }}</td>
                        <td><div class="fw-semibold">{{ $item->program_name }}</div></td>
                        <td class="d-none d-md-table-cell text-muted small">{{ Str::limit($item->description, 90) }}</td>
                        <td class="text-end">
                            <a href="{{ route('admin.health.edit', $item) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil"></i></a>
                            <form action="{{ route('admin.health.destroy', $item) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus program ini?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="text-center text-muted py-4">Belum ada program kesehatan.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-3">{{ $items->links() }}</div>
@endsection
