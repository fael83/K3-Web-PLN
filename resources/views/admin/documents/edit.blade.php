@extends('layouts.admin')

@section('title', 'Edit Dokumen')

@section('content')
<div class="container-fluid">
    <div class="d-flex align-items-center mb-4">
        <a href="{{ route('admin.documents.show', $document) }}" class="btn btn-outline-secondary me-3">
            <i class="bi bi-arrow-left"></i>
        </a>
        <div>
            <h4 class="fw-bold mb-0">Edit Dokumen</h4>
            <div class="text-muted small">
                Perbarui metadata draft atau upload file revisi baru.
            </div>
        </div>
    </div>

    @if($document->status !== 'draft')
        <div class="alert alert-warning">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            Dokumen ini saat ini berstatus <strong>{{ ucfirst(str_replace('_', ' ', $document->status)) }}</strong>.
            Sesuai alur baru, hanya dokumen draft yang boleh diedit.
        </div>
    @endif

    @if(!empty($document->review_note))
        <div class="alert alert-warning">
            <div class="fw-semibold mb-1">
                <i class="bi bi-chat-left-text me-1"></i> Catatan Review Sebelumnya
            </div>
            <div>{{ $document->review_note }}</div>
        </div>
    @endif

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
            <form action="{{ route('admin.documents.update', $document) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row g-3">
                    <div class="col-md-8">
                        <label class="form-label fw-semibold">Judul Dokumen <span class="text-danger">*</span></label>
                        <input type="text"
                               name="title"
                               class="form-control @error('title') is-invalid @enderror"
                               value="{{ old('title', $document->title) }}"
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
                               value="{{ old('document_number', $document->document_number) }}"
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
                                <option value="{{ $val }}" {{ old('category', $document->category) == $val ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                        @error('category')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Revisi Aktif</label>
                        <input type="text"
                               class="form-control"
                               value="Rev. {{ $document->revision_number ?? 1 }}"
                               readonly>
                        <div class="form-text">
                            Nomor revisi akan naik otomatis jika Anda upload file baru.
                        </div>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Status Saat Ini</label>
                        <input type="text"
                               class="form-control"
                               value="{{ ucfirst(str_replace('_', ' ', $document->status)) }}"
                               readonly>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Departemen Pemilik</label>
                        <input type="text"
                               name="owner_department"
                               class="form-control @error('owner_department') is-invalid @enderror"
                               value="{{ old('owner_department', $document->owner_department) }}"
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
                               value="{{ old('effective_date', $document->effective_date?->format('Y-m-d')) }}">
                        @error('effective_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Tanggal Review</label>
                        <input type="date"
                               name="review_date"
                               class="form-control @error('review_date') is-invalid @enderror"
                               value="{{ old('review_date', $document->review_date?->format('Y-m-d')) }}">
                        @error('review_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-12">
                        <label class="form-label fw-semibold">Ganti File (Opsional)</label>
                        <input type="file"
                               name="file"
                               class="form-control @error('file') is-invalid @enderror"
                               accept=".pdf,.doc,.docx,.xls,.xlsx,.jpg,.jpeg,.png">
                        <div class="form-text">
                            File saat ini:
                            <a href="{{ $document->file_url }}" target="_blank">Lihat File</a>
                            — kosongkan jika tidak ingin mengganti file. Maksimal 50MB.
                        </div>
                        @error('file')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-12">
                        <label class="form-label fw-semibold">Catatan Perubahan</label>
                        <textarea name="change_notes"
                                  class="form-control @error('change_notes') is-invalid @enderror"
                                  rows="3"
                                  placeholder="Opsional, jelaskan apa yang berubah pada revisi ini...">{{ old('change_notes') }}</textarea>
                        <div class="form-text">
                            Catatan ini akan disimpan ke riwayat revisi jika Anda mengupload file baru.
                        </div>
                        @error('change_notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12">
                        <label class="form-label fw-semibold">Deskripsi</label>
                        <textarea name="description"
                                  class="form-control @error('description') is-invalid @enderror"
                                  rows="4">{{ old('description', $document->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <hr class="my-4">

                <div class="d-flex flex-wrap gap-2">
                    <button type="submit" class="btn btn-warning">
                        <i class="bi bi-save me-1"></i> Simpan Perubahan
                    </button>

                    <a href="{{ route('admin.documents.show', $document) }}" class="btn btn-outline-secondary">
                        Kembali ke Detail
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection