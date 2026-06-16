@extends('layouts.admin')
@section('title', $item->exists ? 'Edit Langkah SOP' : 'Tambah Langkah SOP')

@section('content')
@include('admin.partials._header', [
    'heading'    => $item->exists ? 'Edit Langkah SOP' : 'Tambah Langkah SOP',
    'subheading' => 'Tentukan sektor, urutan, dan deskripsi langkah.',
])

@include('admin.partials._errors')

<div class="card border-0 shadow-sm">
    <div class="card-body p-4">
        <form method="POST" action="{{ $item->exists ? route('admin.sop.update', $item) : route('admin.sop.store') }}">
            @csrf
            @if ($item->exists) @method('PUT') @endif

            <div class="row g-3">
                <div class="col-md-8">
                    <label class="form-label">Sektor <span class="text-danger">*</span></label>
                    <select name="sector" class="form-select" required>
                        <option value="">— Pilih sektor —</option>
                        @foreach (\App\Models\SopStep::SECTORS as $key => $label)
                            <option value="{{ $key }}" {{ old('sector', $item->sector) === $key ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Urutan <span class="text-danger">*</span></label>
                    <input type="number" name="step_order" class="form-control" min="0" required
                           value="{{ old('step_order', $item->step_order ?? 1) }}">
                </div>

                <div class="col-12">
                    <label class="form-label">Judul Langkah <span class="text-danger">*</span></label>
                    <input type="text" name="title" class="form-control" required
                           value="{{ old('title', $item->title) }}">
                </div>

                <div class="col-12">
                    <label class="form-label">Deskripsi</label>
                    <textarea name="description" class="form-control" rows="4">{{ old('description', $item->description) }}</textarea>
                </div>
            </div>

            <div class="d-flex gap-2 mt-4">
                <button class="btn btn-pln"><i class="bi bi-save me-1"></i> Simpan</button>
                <a href="{{ route('admin.sop.index') }}" class="btn btn-light">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
