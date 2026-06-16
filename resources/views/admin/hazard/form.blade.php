@extends('layouts.admin')
@section('title', $item->exists ? 'Edit Bahaya' : 'Tambah Bahaya')

@section('content')
@include('admin.partials._header', [
    'heading'    => $item->exists ? 'Edit Bahaya' : 'Tambah Bahaya',
    'subheading' => 'Identifikasi potensi bahaya dan langkah pengendaliannya.',
])

@include('admin.partials._errors')

<div class="card border-0 shadow-sm">
    <div class="card-body p-4">
        <form method="POST" action="{{ $item->exists ? route('admin.hazard.update', $item) : route('admin.hazard.store') }}">
            @csrf
            @if ($item->exists) @method('PUT') @endif

            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Kategori <span class="text-danger">*</span></label>
                    <select name="category" class="form-select" required>
                        <option value="">— Pilih kategori —</option>
                        @foreach (\App\Models\Hazard::CATEGORIES as $key => $label)
                            <option value="{{ $key }}" {{ old('category', $item->category) === $key ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Nama Bahaya <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control" required value="{{ old('name', $item->name) }}">
                </div>

                <div class="col-12">
                    <label class="form-label">Deskripsi</label>
                    <textarea name="description" class="form-control" rows="3">{{ old('description', $item->description) }}</textarea>
                </div>

                <div class="col-md-12">
                    <label class="form-label">Lokasi</label>
                    <input type="text" name="location" class="form-control" value="{{ old('location', $item->location) }}">
                </div>

                <div class="col-md-6">
                    <label class="form-label">Kemungkinan (Likelihood) <span class="text-danger">*</span></label>
                    <select name="likelihood" class="form-select" required>
                        @foreach (\App\Models\Hazard::LEVELS as $key => $label)
                            <option value="{{ $key }}" {{ old('likelihood', $item->likelihood ?? 'sedang') === $key ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Keparahan (Severity) <span class="text-danger">*</span></label>
                    <select name="severity" class="form-select" required>
                        @foreach (\App\Models\Hazard::LEVELS as $key => $label)
                            <option value="{{ $key }}" {{ old('severity', $item->severity ?? 'sedang') === $key ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-12">
                    <label class="form-label">Pengendalian (Control Measure)</label>
                    <textarea name="control_measure" class="form-control" rows="3">{{ old('control_measure', $item->control_measure) }}</textarea>
                </div>
            </div>

            <div class="d-flex gap-2 mt-4">
                <button class="btn btn-pln"><i class="bi bi-save me-1"></i> Simpan</button>
                <a href="{{ route('admin.hazard.index') }}" class="btn btn-light">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
