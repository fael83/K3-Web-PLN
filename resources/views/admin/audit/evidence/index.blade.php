@extends('layouts.admin')
@section('title', 'Evidence Package')

@section('content')
@include('admin.partials._header', [
    'heading'    => 'Evidence Package',
    'subheading' => 'Kumpulkan dan ekspor semua bukti audit dalam satu laporan.',
])

<div class="row g-3">
    <div class="col-md-5">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-transparent fw-semibold">
                <i class="bi bi-archive me-2 text-primary"></i>Generate Evidence Package
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.audit-evidence.generate') }}" target="_blank">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Pilih Audit <span class="text-danger">*</span></label>
                        <select name="audit_id" class="form-select" required>
                            <option value="">-- Pilih Audit --</option>
                            @foreach($audits as $audit)
                                <option value="{{ $audit->id }}">
                                    {{ $audit->title }}
                                    ({{ \App\Models\AuditChecklist::STATUSES[$audit->status] }})
                                </option>
                            @endforeach
                        </select>
                        @if($audits->isEmpty())
                            <div class="form-text text-warning">
                                <i class="bi bi-exclamation-triangle me-1"></i>
                                Belum ada audit yang selesai. Selesaikan audit terlebih dahulu.
                            </div>
                        @endif
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Periode Bukti (Opsional)</label>
                        <div class="row g-2">
                            <div class="col">
                                <input type="date" name="date_from" class="form-control" placeholder="Dari tanggal">
                            </div>
                            <div class="col">
                                <input type="date" name="date_to" class="form-control" placeholder="Sampai tanggal">
                            </div>
                        </div>
                        <div class="form-text">Kosongkan untuk mengambil semua data.</div>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-file-earmark-pdf me-2"></i> Generate Laporan PDF
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-7">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-transparent fw-semibold">
                <i class="bi bi-info-circle me-2 text-info"></i>Isi Evidence Package
            </div>
            <div class="card-body">
                <p class="text-muted small mb-3">
                    Sistem akan mengumpulkan secara otomatis semua bukti yang tersimpan pada periode yang dipilih:
                </p>
                <div class="list-group list-group-flush">
                    <div class="list-group-item px-0 d-flex align-items-center gap-3">
                        <span class="card-icon bg-success-subtle" style="width:36px;height:36px;font-size:.9rem;">
                            <i class="bi bi-clipboard-check text-success"></i>
                        </span>
                        <div>
                            <div class="fw-semibold small">Hasil Audit Checklist</div>
                            <div class="text-muted" style="font-size:.78rem;">Seluruh item, status, temuan, dan tindakan perbaikan</div>
                        </div>
                    </div>
                    <div class="list-group-item px-0 d-flex align-items-center gap-3">
                        <span class="card-icon bg-warning-subtle" style="width:36px;height:36px;font-size:.9rem;">
                            <i class="bi bi-clock-history text-warning"></i>
                        </span>
                        <div>
                            <div class="fw-semibold small">Audit Trail</div>
                            <div class="text-muted" style="font-size:.78rem;">Log aktivitas pengguna pada periode terpilih</div>
                        </div>
                    </div>
                    <div class="list-group-item px-0 d-flex align-items-center gap-3">
                        <span class="card-icon bg-danger-subtle" style="width:36px;height:36px;font-size:.9rem;">
                            <i class="bi bi-exclamation-triangle text-danger"></i>
                        </span>
                        <div>
                            <div class="fw-semibold small">Laporan Insiden</div>
                            <div class="text-muted" style="font-size:.78rem;">Semua insiden dan kecelakaan kerja yang tercatat</div>
                        </div>
                    </div>
                    <div class="list-group-item px-0 d-flex align-items-center gap-3">
                        <span class="card-icon bg-primary-subtle" style="width:36px;height:36px;font-size:.9rem;">
                            <i class="bi bi-person-check text-primary"></i>
                        </span>
                        <div>
                            <div class="fw-semibold small">Rekap Aktivitas Sistem</div>
                            <div class="text-muted" style="font-size:.78rem;">Rekaman siapa melakukan apa dan kapan</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
