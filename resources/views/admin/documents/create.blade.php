@extends('layouts.admin')

@section('title', 'Tambah Dokumen')

@section('content')
<div class="container-fluid">

    <div class="d-flex align-items-center mb-4">
        <a href="{{ route('admin.documents.index') }}" class="btn btn-outline-secondary me-3">
            <i class="bi bi-arrow-left"></i>
        </a>
        <h4 class="fw-bold mb-0">Tambah Dokumen Baru</h4>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <form action="{{ route('admin.documents.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="row g-3">

                    {{-- Judul --}}
                    <div class="col-md-8">
                        <label class="form-label fw-semibold">Judul Dokumen <span class="text-danger">*</span></label>
                        <input type="text" name="title"
                               class="form-control @error('title') is-invalid @enderror"
                               value="{{ old('title') }}"
                               placeholder="Contoh: SOP Penggunaan APD" required>
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Nomor Dokumen --}}
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Nomor Dokumen <span class="text-danger">*</span></label>
                        <input type="text" name="document_number"
                               class="form-control @error('document_number') is-invalid @enderror"
                               value="{{ old('document_number') }}"
                               placeholder="Contoh: SOP/K3/001" required>
                        @error('document_number')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Kategori --}}
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Kategori <span class="text-danger">*</span></label>
                        <select name="category" class="form-select @error('category') is-invalid @enderror" required>
                            <option value="">-- Pilih Kategori --</option>
                            <option value="policy"        {{ old('category') == 'policy'        ? 'selected' : '' }}>Policy (Kebijakan)</option>
                            <option value="standard"      {{ old('category') == 'standard'      ? 'selected' : '' }}>Standard (Standar)</option>
                            <option value="procedure"     {{ old('category') == 'procedure'     ? 'selected' : '' }}>Procedure (Prosedur/SOP)</option>
                            <option value="legal"         {{ old('category') == 'legal'         ? 'selected' : '' }}>Legal (Regulasi)</option>
                            <option value="form_template" {{ old('category') == 'form_template' ? 'selected' : '' }}>Form Template</option>
                            <option value="record"        {{ old('category') == 'record'        ? 'selected' : '' }}>Record (Rekaman)</option>
                            <option value="emergency"     {{ old('category') == 'emergency'     ? 'selected' : '' }}>Emergency (Darurat)</option>
                        </select>
                        @error('category')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Versi --}}
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Versi</label>
                        <input type="text" name="version" class="form-control"
                               value="{{ old('version') }}" placeholder="Contoh: v1.0">
                    </div>

                    {{-- Departemen Pemilik --}}
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Departemen Pemilik</label>
                        <input type="text" name="owner_department" class="form-control"
                               value="{{ old('owner_department') }}"
                               placeholder="Contoh: K3, HR, Engineering">
                    </div>

                    {{-- Tanggal Efektif --}}
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Tanggal Efektif</label>
                        <input type="date" name="effective_date"
                               class="form-control @error('effective_date') is-invalid @enderror"
                               value="{{ old('effective_date') }}">
                        @error('effective_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Tanggal Review --}}
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Tanggal Review</label>
                        <input type="date" name="review_date"
                               class="form-control @error('review_date') is-invalid @enderror"
                               value="{{ old('review_date') }}">
                        @error('review_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- File --}}
                    <div class="col-md-12">
                        <label class="form-label fw-semibold">File Dokumen <span class="text-danger">*</span></label>
                        <input type="file" name="file"
                               class="form-control @error('file') is-invalid @enderror"
                               accept=".pdf,.doc,.docx,.xls,.xlsx" required>
                        <div class="form-text">Format: PDF, DOC, DOCX, XLS, XLSX. Maks 50MB.</div>
                        @if($errors->any() && !$errors->has('file'))
                            <div class="alert alert-warning mt-2 py-2 small">
                                ⚠️ Ada error pada form. Silakan pilih ulang file dokumen.
                            </div>
                        @endif
                        @error('file')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Deskripsi --}}
                    <div class="col-12">
                        <label class="form-label fw-semibold">Deskripsi</label>
                        <textarea name="description" class="form-control" rows="3"
                                  placeholder="Deskripsi singkat dokumen...">{{ old('description') }}</textarea>
                    </div>

                </div>

                <hr class="my-4">

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-upload me-1"></i> Upload Dokumen
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