@extends('layouts.admin')
@section('title', $item->exists ? 'Edit Program Kesehatan' : 'Tambah Program Kesehatan')

@section('content')
@include('admin.partials._header', [
    'heading'    => $item->exists ? 'Edit Program Kesehatan' : 'Tambah Program Kesehatan',
    'subheading' => 'Lengkapi nama dan deskripsi program kesehatan kerja.',
])

@include('admin.partials._errors')

<div class="card border-0 shadow-sm">
    <div class="card-body p-4">
        <form method="POST" action="{{ $item->exists ? route('admin.health.update', $item) : route('admin.health.store') }}">
            @csrf
            @if ($item->exists) @method('PUT') @endif

            <div class="row g-3">
                <div class="col-md-8">
                    <label class="form-label">Nama Program <span class="text-danger">*</span></label>
                    <input type="text" name="program_name" class="form-control" required value="{{ old('program_name', $item->program_name) }}">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Urutan <span class="text-danger">*</span></label>
                    <input type="number" name="sort_order" class="form-control" min="0" required
                           value="{{ old('sort_order', $item->sort_order ?? 1) }}">
                </div>

                <div class="col-12">
                    <label class="form-label">Deskripsi</label>
                    <textarea name="description" class="form-control" rows="4">{{ old('description', $item->description) }}</textarea>
                </div>
            </div>

            <div class="d-flex gap-2 mt-4">
                <button class="btn btn-pln"><i class="bi bi-save me-1"></i> Simpan</button>
                <a href="{{ route('admin.health.index') }}" class="btn btn-light">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
