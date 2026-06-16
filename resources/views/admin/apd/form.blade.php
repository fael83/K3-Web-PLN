@extends('layouts.admin')
@section('title', $item->exists ? 'Edit APD' : 'Tambah APD')

@section('content')
@include('admin.partials._header', [
    'heading'    => $item->exists ? 'Edit APD' : 'Tambah APD',
    'subheading' => 'Lengkapi informasi alat pelindung diri.',
])

@include('admin.partials._errors')

<div class="card border-0 shadow-sm">
    <div class="card-body p-4">
        <form method="POST"
              action="{{ $item->exists ? route('admin.apd.update', $item) : route('admin.apd.store') }}"
              enctype="multipart/form-data">
            @csrf
            @if ($item->exists) @method('PUT') @endif

            <div class="row g-3">
                <div class="col-md-8">
                    <label class="form-label">Nama APD <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control" required
                           value="{{ old('name', $item->name) }}">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Status</label>
                    <div class="form-check form-switch mt-2">
                        <input type="hidden" name="is_active" value="0">
                        <input class="form-check-input" type="checkbox" role="switch" id="is_active"
                               name="is_active" value="1"
                               {{ old('is_active', $item->is_active) ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_active">Tampilkan di situs publik</label>
                    </div>
                </div>

                <div class="col-12">
                    <label class="form-label">Fungsi</label>
                    <textarea name="function" class="form-control" rows="3">{{ old('function', $item->function) }}</textarea>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Area Penggunaan</label>
                    <input type="text" name="usage_area" class="form-control"
                           value="{{ old('usage_area', $item->usage_area) }}">
                </div>

                <div class="col-md-6">
                    <label class="form-label">Standar / Acuan</label>
                    <input type="text" name="standard" class="form-control"
                           value="{{ old('standard', $item->standard) }}"
                           placeholder="cth: SNI / ANSI / IEC">
                </div>

                <div class="col-12">
                    <label class="form-label">Gambar APD</label>
                    @if ($item->image_url)
                        <div class="mb-2">
                            <img src="{{ $item->image_url }}" alt="" class="rounded border" style="height:90px;object-fit:cover;">
                        </div>
                    @endif
                    <input type="file" name="image" class="form-control" accept="image/*">
                    <div class="form-text">Format gambar maks. 4 MB. Kosongkan bila tidak ingin mengubah.</div>
                </div>
            </div>

            <div class="d-flex gap-2 mt-4">
                <button class="btn btn-pln"><i class="bi bi-save me-1"></i> Simpan</button>
                <a href="{{ route('admin.apd.index') }}" class="btn btn-light">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
