@extends('layouts.admin')
@section('title', 'Detail Audit: ' . $auditChecklist->title)

@php
    $statusClass = [
        'pending'     => 'secondary',
        'conformance' => 'success',
        'minor_nc'    => 'warning',
        'major_nc'    => 'danger',
        'observation' => 'info',
    ];
    $auditStatusClass = [
        'draft'       => 'secondary',
        'in_progress' => 'warning',
        'completed'   => 'success',
    ];
@endphp

@section('content')
@include('admin.partials._header', [
    'heading'    => $auditChecklist->title,
    'subheading' => \App\Models\AuditChecklist::TYPES[$auditChecklist->audit_type] . ' · ' .
                    ($auditChecklist->audit_date?->format('d M Y') ?? 'Tanggal belum ditentukan'),
])

{{-- Summary bar --}}
<div class="row g-3 mb-3">
    @php $total = $auditChecklist->items->count(); @endphp
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm text-center p-3">
            <div class="display-6 fw-bold text-success">{{ $auditChecklist->conformance_count }}</div>
            <div class="small text-muted">Conformance</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm text-center p-3">
            <div class="display-6 fw-bold text-warning">{{ $auditChecklist->minor_nc_count }}</div>
            <div class="small text-muted">Minor NC</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm text-center p-3">
            <div class="display-6 fw-bold text-danger">{{ $auditChecklist->major_nc_count }}</div>
            <div class="small text-muted">Major NC</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm text-center p-3">
            <div class="display-6 fw-bold text-info">{{ $auditChecklist->observation_count }}</div>
            <div class="small text-muted">Observation</div>
        </div>
    </div>
</div>

<div class="row g-3">
    <div class="col-md-8">
        {{-- Item Checklist --}}
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-transparent d-flex justify-content-between align-items-center">
                <span class="fw-semibold"><i class="bi bi-list-check me-2"></i>Item Checklist ({{ $total }} item)</span>
                <span class="badge bg-{{ $auditStatusClass[$auditChecklist->status] }}-subtle text-{{ $auditStatusClass[$auditChecklist->status] }}-emphasis">
                    {{ \App\Models\AuditChecklist::STATUSES[$auditChecklist->status] }}
                </span>
            </div>
            <div class="card-body p-0">
                @forelse($auditChecklist->items as $i => $item)
                <div class="border-bottom p-3">
                    <div class="d-flex align-items-start justify-content-between">
                        <div class="flex-grow-1">
                            <div class="d-flex align-items-center gap-2 mb-1">
                                <span class="badge bg-light text-dark border small">{{ $i + 1 }}</span>
                                <span class="fw-semibold">{{ $item->item_name }}</span>
                                <span class="badge bg-{{ $statusClass[$item->status] }}-subtle text-{{ $statusClass[$item->status] }}-emphasis ms-auto">
                                    {{ \App\Models\AuditItem::STATUSES[$item->status] }}
                                </span>
                            </div>
                            @if($item->description)
                                <p class="text-muted small mb-1">{{ $item->description }}</p>
                            @endif
                            @if($item->finding)
                                <div class="alert alert-warning py-1 px-2 small mb-1">
                                    <i class="bi bi-exclamation-triangle me-1"></i><strong>Temuan:</strong> {{ $item->finding }}
                                </div>
                            @endif
                            @if($item->corrective_action)
                                <div class="alert alert-info py-1 px-2 small mb-0">
                                    <i class="bi bi-tools me-1"></i><strong>Tindakan Perbaikan:</strong> {{ $item->corrective_action }}
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Form update item --}}
                    <div class="mt-2">
                        <a class="btn btn-outline-secondary btn-sm" data-bs-toggle="collapse"
                           href="#item-form-{{ $item->id }}">
                            <i class="bi bi-pencil me-1"></i> Nilai Item
                        </a>
                        <form method="POST" action="{{ route('admin.audit-checklist.item.update', [$auditChecklist, $item]) }}"
                              class="collapse mt-2 border rounded p-3 bg-light-subtle" id="item-form-{{ $item->id }}">
                            @csrf @method('PUT')
                            <div class="row g-2">
                                <div class="col-md-4">
                                    <label class="form-label small fw-semibold">Status Penilaian</label>
                                    <select name="status" class="form-select form-select-sm">
                                        @foreach(\App\Models\AuditItem::STATUSES as $val => $label)
                                            <option value="{{ $val }}" {{ $item->status === $val ? 'selected' : '' }}>
                                                {{ $label }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-8">
                                    <label class="form-label small fw-semibold">Referensi Bukti</label>
                                    <input type="text" name="evidence_ref" class="form-control form-control-sm"
                                           value="{{ $item->evidence_ref }}"
                                           placeholder="cth: SOP-K3-001, Laporan Insiden #5">
                                </div>
                                <div class="col-12">
                                    <label class="form-label small fw-semibold">Temuan Audit</label>
                                    <textarea name="finding" class="form-control form-control-sm" rows="2"
                                              placeholder="Catatan temuan auditor...">{{ $item->finding }}</textarea>
                                </div>
                                <div class="col-12">
                                    <label class="form-label small fw-semibold">Rekomendasi Tindakan Perbaikan</label>
                                    <textarea name="corrective_action" class="form-control form-control-sm" rows="2"
                                              placeholder="Saran tindakan perbaikan...">{{ $item->corrective_action }}</textarea>
                                </div>
                                <div class="col-12 d-flex gap-2">
                                    <button type="submit" class="btn btn-primary btn-sm">Simpan</button>
                                    <form method="POST"
                                          action="{{ route('admin.audit-checklist.item.destroy', [$auditChecklist, $item]) }}"
                                          onsubmit="return confirm('Hapus item ini?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger btn-sm">
                                            <i class="bi bi-trash"></i> Hapus Item
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                @empty
                    <div class="p-4 text-center text-muted">Belum ada item checklist.</div>
                @endforelse
            </div>

            {{-- Tambah item baru --}}
            <div class="card-footer bg-transparent">
                <a class="btn btn-outline-primary btn-sm" data-bs-toggle="collapse" href="#add-item-form">
                    <i class="bi bi-plus-circle me-1"></i> Tambah Item
                </a>
                <form method="POST" action="{{ route('admin.audit-checklist.item.store', $auditChecklist) }}"
                      class="collapse mt-2 border rounded p-3 bg-light-subtle" id="add-item-form">
                    @csrf
                    <div class="row g-2">
                        <div class="col-md-8">
                            <input type="text" name="item_name" class="form-control form-control-sm"
                                   placeholder="Nama item checklist" required>
                        </div>
                        <div class="col-md-4">
                            <input type="text" name="description" class="form-control form-control-sm"
                                   placeholder="Deskripsi (opsional)">
                        </div>
                        <div class="col-auto">
                            <button type="submit" class="btn btn-primary btn-sm">Tambah</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Sidebar info --}}
    <div class="col-md-4">
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-header bg-transparent fw-semibold">Info Audit</div>
            <div class="card-body small">
                <dl class="row mb-0">
                    <dt class="col-5 text-muted">Tipe</dt>
                    <dd class="col-7">{{ \App\Models\AuditChecklist::TYPES[$auditChecklist->audit_type] }}</dd>
                    <dt class="col-5 text-muted">Tanggal</dt>
                    <dd class="col-7">{{ $auditChecklist->audit_date?->format('d M Y') ?? '—' }}</dd>
                    <dt class="col-5 text-muted">Auditor</dt>
                    <dd class="col-7">{{ $auditChecklist->auditor_name ?? '—' }}</dd>
                    <dt class="col-5 text-muted">Dibuat oleh</dt>
                    <dd class="col-7">{{ $auditChecklist->creator->name ?? 'Sistem' }}</dd>
                    <dt class="col-5 text-muted">Dibuat</dt>
                    <dd class="col-7">{{ $auditChecklist->created_at?->format('d M Y') }}</dd>
                </dl>
            </div>
        </div>

        <div class="d-grid gap-2">
            <a href="{{ route('admin.audit-checklist.edit', $auditChecklist) }}" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-pencil me-1"></i> Edit Info Audit
            </a>
            <a href="{{ route('admin.audit-evidence.index') }}" class="btn btn-outline-primary btn-sm">
                <i class="bi bi-archive me-1"></i> Generate Evidence Package
            </a>
            <a href="{{ route('admin.audit-checklist.index') }}" class="btn btn-light btn-sm">
                <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar
            </a>
        </div>
    </div>
</div>
@endsection
