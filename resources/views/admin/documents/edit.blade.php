@extends('layouts.admin')

@section('title', 'Edit Dokumen')

@section('content')
<div class="container-fluid">

    <div class="d-flex align-items-center mb-4">
        <a href="{{ route('admin.documents.index') }}" class="btn btn-outline-secondary me-3">
            <i class="bi bi-arrow-left"></i>
        </a>
        <h4 class="fw-bold mb-0">Edit Dokumen</h4>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <form action="{{ route('admin.documents.update', $document) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row g-3">

                    {{-- Judul --}}
                    <div class="col-md-8">
                        <label class="form-label fw-semibold">Judul Dokumen <span class="text-danger">*</span></label>
                        <input type="text" name="title"
                               class="form-control @error('title') is-invalid @enderror"
                               value="{{ old('title', $document->title) }}" required>
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Nomor Dokumen --}}
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Nomor Dokumen <span class="text-danger">*</span></label>
                        <input type="text" name="document_number"
                               class="form-control @error('document_number') is-invalid @enderror"
                               value="{{ old('document_number', $document->document_number) }}" required>
                        @error('document_number')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Kategori --}}
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Kategori <span class="text-danger">*</span></label>
                        <select name="category" class="form-select @error('category') is-invalid @enderror" required>
                            <option value="">-- Pilih Kategori --</option>
                            @foreach([
                                'policy'        => 'Policy (Kebijakan)',
                                'standard'      => 'Standard (Standar)',
                                'procedure'     => 'Procedure (Prosedur/SOP)',
                                'legal'         => 'Legal (Regulasi)',
                                'form_template' => 'Form Template',
                                'record'        => 'Record (Rekaman)',
                                'emergency'     => 'Emergency (Darurat)',
                            ] as $val => $label)
                                <option value="{{ $val }}"
                                    {{ old('category', $document->category) == $val ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                        @error('category')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Versi --}}
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Versi</label>
                        <input type="text" name="version" class="form-control"
                               value="{{ old('version', $document->version) }}">
                    </div>

                    {{-- Departemen Pemilik --}}
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Departemen Pemilik</label>
                        <input type="text" name="owner_department" class="form-control"
                               value="{{ old('owner_department', $document->owner_department) }}"
                               placeholder="Contoh: K3, HR, Engineering">
                    </div>

                    {{-- Tanggal Efektif --}}
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Tanggal Efektif</label>
                        <input type="date" name="effective_date"
                               class="form-control @error('effective_date') is-invalid @enderror"
                               value="{{ old('effective_date', $document->effective_date?->format('Y-m-d')) }}">
                        @error('effective_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Tanggal Review --}}
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Tanggal Review</label>
                        <input type="date" name="review_date"
                               class="form-control @error('review_date') is-invalid @enderror"
                               value="{{ old('review_date', $document->review_date?->format('Y-m-d')) }}">
                        @error('review_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Ganti File --}}
                    <div class="col-md-12">
                        <label class="form-label fw-semibold">Ganti File (Opsional)</label>
                        <input type="file" name="file"
                               class="form-control @error('file') is-invalid @enderror"
                               accept=".pdf,.doc,.docx,.xls,.xlsx">
                        <div class="form-text">
                            File saat ini:
                            <a href="{{ $document->file_url }}" target="_blank">Lihat File</a>
                            — Kosongkan jika tidak ingin mengganti. Maks 50MB.
                        </div>
                        @error('file')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Deskripsi --}}
                    <div class="col-12">
                        <label class="form-label fw-semibold">Deskripsi</label>
                        <textarea name="description" class="form-control" rows="3">{{ old('description', $document->description) }}</textarea>
                    </div>

                </div>

                <hr class="my-4">

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-warning">
                        <i class="bi bi-save me-1"></i> Simpan Perubahan
                    </button>
                    <a href="{{ route('admin.documents.index') }}" class="btn btn-outline-secondary">
                        Batal
                    </a>
                </div>

            </form>
        </div>
    </div>

</div>
@endsection