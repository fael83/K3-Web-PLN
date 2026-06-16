@extends('layouts.admin')
@section('title', $item->exists ? 'Edit Anggota Tim' : 'Tambah Anggota Tim')

@section('content')
@include('admin.partials._header', [
    'heading'    => $item->exists ? 'Edit Anggota Tim' : 'Tambah Anggota Tim',
    'subheading' => 'Lengkapi jabatan dan tanggung jawab anggota tim K3.',
])

@include('admin.partials._errors')

<div class="card border-0 shadow-sm">
    <div class="card-body p-4">
        <form method="POST" action="{{ $item->exists ? route('admin.team.update', $item) : route('admin.team.store') }}">
            @csrf
            @if ($item->exists) @method('PUT') @endif

            <div class="row g-3">
                <div class="col-md-8">
                    <label class="form-label">Jabatan <span class="text-danger">*</span></label>
                    <input type="text" name="position" class="form-control" required value="{{ old('position', $item->position) }}">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Urutan <span class="text-danger">*</span></label>
                    <input type="number" name="sort_order" class="form-control" min="0" required
                           value="{{ old('sort_order', $item->sort_order ?? 1) }}">
                </div>

                <div class="col-md-6">
                    <label class="form-label">Nama Pejabat</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', $item->name) }}">
                </div>

                <div class="col-md-6">
                    <label class="form-label">Level <span class="text-danger">*</span></label>
                    <select name="level" class="form-select" required>
                        @foreach (\App\Models\TeamMember::LEVELS as $key => $label)
                            <option value="{{ $key }}" {{ old('level', $item->level ?? 'anggota') === $key ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-12">
                    <label class="form-label">Tanggung Jawab</label>
                    <textarea name="responsibility" class="form-control" rows="4">{{ old('responsibility', $item->responsibility) }}</textarea>
                </div>
            </div>

            <div class="d-flex gap-2 mt-4">
                <button class="btn btn-pln"><i class="bi bi-save me-1"></i> Simpan</button>
                <a href="{{ route('admin.team.index') }}" class="btn btn-light">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
