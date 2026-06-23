@extends('layouts.admin')

@section('title', 'Tambah Dokumen')

@section('content')
<div class="container-fluid">
    <div class="d-flex align-items-center mb-4">
        <a href="{{ route('admin.documents.index') }}" class="btn btn-outline-secondary me-3">
            <i class="bi bi-arrow-left"></i>
        </a>
        <div>
            <h4 class="fw-bold mb-0">Tambah Dokumen Baru</h4>
            <div class="text-muted small">
                Upload dokumen baru. Dokumen akan dibuat sebagai draft dan bisa diajukan review setelah diperiksa.
            </div>
        </div>
    </div>

    <div class="alert alert-info">
        <i class="bi bi-info-circle-fill me-2"></i>
        Dokumen baru akan disimpan dengan status <strong>Draft</strong> dan revisi awal <strong>Rev. 1</strong>.
    </div>

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <div class="fw-semibold mb-1">Terjadi kesalahan:</div>
            <ul class="mb-0 ps-3">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <form action="{{ route('admin.documents.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="row g-3">
                    <div class="col-md-8">
                        <label class="form-label fw-semibold">Judul Dokumen <span class="text-danger">*</span></label>
                        <input type="text"
                               name="title"
                               class="form-control @error('title') is-invalid @enderror"
                               value="{{ old('title') }}"
                               placeholder="Contoh: SOP Penggunaan APD"
                               required>
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Nomor Dokumen <span class="text-danger">*</span></label>
                        <input type="text"
                               name="document_number"
                               class="form-control @error('document_number') is-invalid @enderror"
                               value="{{ old('document_number') }}"
                               placeholder="Contoh: SOP/K3/001"
                               required>
                        @error('document_number')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

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
                                <option value="{{ $val }}" {{ old('category') == $val ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                        @error('category')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Status Awal</label>
                        <input type="text" class="form-control" value="Draft" readonly>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Revisi Awal</label>
                        <input type="text" class="form-control" value="Rev. 1" readonly>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Departemen Pemilik</label>
                        <input type="text"
                               name="owner_department"
                               class="form-control @error('owner_department') is-invalid @enderror"
                               value="{{ old('owner_department') }}"
                               placeholder="Contoh: K3, HR, Engineering">
                        @error('owner_department')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Tanggal Efektif</label>
                        <input type="date"
                               name="effective_date"
                               class="form-control @error('effective_date') is-invalid @enderror"
                               value="{{ old('effective_date') }}">
                        @error('effective_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Tanggal Review</label>
                        <input type="date"
                               name="review_date"
                               class="form-control @error('review_date') is-invalid @enderror"
                               value="{{ old('review_date') }}">
                        @error('review_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-12">
                        <label class="form-label fw-semibold">File Dokumen <span class="text-danger">*</span></label>
                        <input type="file"
                               name="file"
                               class="form-control @error('file') is-invalid @enderror"
                               accept=".pdf,.doc,.docx,.xls,.xlsx,.jpg,.jpeg,.png"
                               required>
                        <div class="form-text">
                            Format yang didukung: PDF, DOC, DOCX, XLS, XLSX, JPG, JPEG, PNG. Maksimal 50MB.
                        </div>

                        @if($errors->any() && !$errors->has('file'))
                            <div class="alert alert-warning mt-2 py-2 small mb-0">
                                ⚠️ Ada error pada form. Karena browser tidak menyimpan input file lama, silakan pilih ulang file dokumen.
                            </div>
                        @endif

                        @error('file')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12">
                        <label class="form-label fw-semibold">Deskripsi</label>
                        <textarea name="description"
                                  class="form-control @error('description') is-invalid @enderror"
                                  rows="4"
                                  placeholder="Deskripsi singkat dokumen...">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <hr class="my-4">

                <div class="d-flex flex-wrap gap-2">
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