@extends('layouts.admin')
@section('title', $audit->exists ? 'Edit Audit' : 'Buat Audit Baru')

@section('content')
@include('admin.partials._header', [
    'heading'    => $audit->exists ? 'Edit Audit' : 'Buat Audit Checklist Baru',
    'subheading' => 'Isi informasi audit dan daftar item yang akan diperiksa.',
])

<form method="POST"
      action="{{ $audit->exists ? route('admin.audit-checklist.update', $audit) : route('admin.audit-checklist.store') }}">
    @csrf
    @if($audit->exists) @method('PUT') @endif

    <div class="row g-3">
        {{-- Info Audit --}}
        <div class="col-md-8">
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header bg-transparent fw-semibold">Informasi Audit</div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Judul Audit <span class="text-danger">*</span></label>
                        <input type="text" name="title" class="form-control @error('title') is-invalid @enderror"
                               value="{{ old('title', $audit->title) }}"
                               placeholder="cth: Audit Internal SMK3 Semester I 2026">
                        @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Deskripsi</label>
                        <textarea name="description" class="form-control" rows="3"
                                  placeholder="Tujuan dan ruang lingkup audit...">{{ old('description', $audit->description) }}</textarea>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Tipe Audit <span class="text-danger">*</span></label>
                            <select name="audit_type" class="form-select">
                                @foreach(\App\Models\AuditChecklist::TYPES as $val => $label)
                                    <option value="{{ $val }}" {{ old('audit_type', $audit->audit_type) === $val ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Status</label>
                            <select name="status" class="form-select">
                                @foreach(\App\Models\AuditChecklist::STATUSES as $val => $label)
                                    <option value="{{ $val }}" {{ old('status', $audit->status) === $val ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Tanggal Audit</label>
                            <input type="date" name="audit_date" class="form-control"
                                   value="{{ old('audit_date', $audit->audit_date?->format('Y-m-d')) }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Nama Auditor</label>
                            <input type="text" name="auditor_name" class="form-control"
                                   value="{{ old('auditor_name', $audit->auditor_name) }}"
                                   placeholder="Nama auditor atau tim audit">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Aksi --}}
        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <button type="submit" class="btn btn-primary w-100 mb-2">
                        <i class="bi bi-save me-1"></i>
                        {{ $audit->exists ? 'Simpan Perubahan' : 'Buat Audit' }}
                    </button>
                    <a href="{{ route('admin.audit-checklist.index') }}" class="btn btn-outline-secondary w-100">
                        Batal
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- Item Checklist (hanya saat buat baru) --}}
    @if(!$audit->exists)
    <div class="card border-0 shadow-sm mt-3">
        <div class="card-header bg-transparent fw-semibold d-flex justify-content-between">
            <span><i class="bi bi-list-check me-2"></i>Item Checklist</span>
            <button type="button" class="btn btn-outline-primary btn-sm" id="addItemBtn">
                <i class="bi bi-plus"></i> Tambah Item
            </button>
        </div>
        <div class="card-body" id="itemsContainer">
            @foreach($defaultItems as $i => $item)
            <div class="item-row border rounded p-2 mb-2 bg-light-subtle" data-index="{{ $i }}">
                <div class="row g-2 align-items-start">
                    <div class="col">
                        <input type="text" name="items[{{ $i }}][item_name]"
                               class="form-control form-control-sm"
                               value="{{ $item['item_name'] }}"
                               placeholder="Nama item checklist">
                        <input type="text" name="items[{{ $i }}][description]"
                               class="form-control form-control-sm mt-1"
                               value="{{ $item['description'] ?? '' }}"
                               placeholder="Deskripsi (opsional)">
                    </div>
                    <div class="col-auto">
                        <button type="button" class="btn btn-outline-danger btn-sm remove-item">
                            <i class="bi bi-trash"></i>
                        </button>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        <div class="card-footer bg-transparent text-muted small">
            <i class="bi bi-info-circle me-1"></i>
            Anda bisa menambah/menghapus item setelah audit dibuat.
        </div>
    </div>
    @endif
</form>

@push('scripts')
<script>
let idx = {{ count($defaultItems ?? []) }};
document.getElementById('addItemBtn')?.addEventListener('click', () => {
    const container = document.getElementById('itemsContainer');
    const div = document.createElement('div');
    div.className = 'item-row border rounded p-2 mb-2 bg-light-subtle';
    div.innerHTML = `
        <div class="row g-2 align-items-start">
            <div class="col">
                <input type="text" name="items[${idx}][item_name]"
                       class="form-control form-control-sm" placeholder="Nama item checklist" required>
                <input type="text" name="items[${idx}][description]"
                       class="form-control form-control-sm mt-1" placeholder="Deskripsi (opsional)">
            </div>
            <div class="col-auto">
                <button type="button" class="btn btn-outline-danger btn-sm remove-item">
                    <i class="bi bi-trash"></i>
                </button>
            </div>
        </div>`;
    container.appendChild(div);
    idx++;
});
document.addEventListener('click', e => {
    if (e.target.closest('.remove-item')) {
        e.target.closest('.item-row').remove();
    }
});
</script>
@endpush
@endsection
