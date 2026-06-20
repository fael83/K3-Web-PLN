@extends('layouts.admin')
@section('title', 'Audit Checklist')

@php
    $statusClass = [
        'draft'       => 'secondary',
        'in_progress' => 'warning',
        'completed'   => 'success',
    ];
    $typeLabel = \App\Models\AuditChecklist::TYPES;
@endphp

@section('content')
@include('admin.partials._header', [
    'heading'    => 'Audit Checklist',
    'subheading' => 'Daftar audit K3 — internal, eksternal, SMK3, dan ISO 45001.',
])

<div class="d-flex justify-content-end mb-3">
    <a href="{{ route('admin.audit-checklist.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle me-1"></i> Buat Audit Baru
    </a>
</div>

<div class="row g-3">
    @forelse($audits as $audit)
        @php
            $total = $audit->items->count();
            $done  = $audit->items->where('status', '!=', 'pending')->count();
            $pct   = $total > 0 ? round($done / $total * 100) : 0;
        @endphp
        <div class="col-md-6 col-xl-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-start justify-content-between mb-2">
                        <span class="badge bg-{{ $statusClass[$audit->status] ?? 'secondary' }}-subtle text-{{ $statusClass[$audit->status] ?? 'secondary' }}-emphasis">
                            {{ \App\Models\AuditChecklist::STATUSES[$audit->status] }}
                        </span>
                        <span class="badge bg-light text-dark border">{{ $typeLabel[$audit->audit_type] ?? $audit->audit_type }}</span>
                    </div>
                    <h6 class="fw-bold mb-1">{{ $audit->title }}</h6>
                    <p class="text-muted small mb-2">
                        <i class="bi bi-calendar3 me-1"></i>
                        {{ $audit->audit_date?->format('d M Y') ?? 'Tanggal belum ditentukan' }}
                        @if($audit->auditor_name)
                            &nbsp;·&nbsp;<i class="bi bi-person me-1"></i>{{ $audit->auditor_name }}
                        @endif
                    </p>

                    {{-- Progress --}}
                    <div class="mb-2">
                        <div class="d-flex justify-content-between small text-muted mb-1">
                            <span>Progres Penilaian</span>
                            <span>{{ $done }}/{{ $total }}</span>
                        </div>
                        <div class="progress" style="height:6px;">
                            <div class="progress-bar bg-primary" style="width:{{ $pct }}%"></div>
                        </div>
                    </div>

                    {{-- Mini stats --}}
                    <div class="d-flex gap-2 flex-wrap mt-2">
                        <span class="badge bg-success-subtle text-success-emphasis">
                            <i class="bi bi-check-circle me-1"></i>{{ $audit->conformance_count }} Conform
                        </span>
                        <span class="badge bg-warning-subtle text-warning-emphasis">
                            <i class="bi bi-exclamation-triangle me-1"></i>{{ $audit->minor_nc_count }} Minor NC
                        </span>
                        <span class="badge bg-danger-subtle text-danger-emphasis">
                            <i class="bi bi-x-circle me-1"></i>{{ $audit->major_nc_count }} Major NC
                        </span>
                        @if($audit->observation_count)
                        <span class="badge bg-info-subtle text-info-emphasis">
                            <i class="bi bi-eye me-1"></i>{{ $audit->observation_count }} OB
                        </span>
                        @endif
                    </div>
                </div>
                <div class="card-footer bg-transparent border-0 d-flex gap-2">
                    <a href="{{ route('admin.audit-checklist.show', $audit) }}" class="btn btn-primary btn-sm flex-grow-1">
                        <i class="bi bi-eye me-1"></i> Buka
                    </a>
                    <a href="{{ route('admin.audit-checklist.edit', $audit) }}" class="btn btn-outline-secondary btn-sm">
                        <i class="bi bi-pencil"></i>
                    </a>
                    <form method="POST" action="{{ route('admin.audit-checklist.destroy', $audit) }}"
                          onsubmit="return confirm('Hapus audit ini?')">
                        @csrf @method('DELETE')
                        <button class="btn btn-outline-danger btn-sm"><i class="bi bi-trash"></i></button>
                    </form>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center py-5 text-muted">
                    <i class="bi bi-clipboard-check display-4 d-block mb-3"></i>
                    Belum ada audit checklist. Buat audit pertama Anda.
                </div>
            </div>
        </div>
    @endforelse
</div>

<div class="mt-3">{{ $audits->links() }}</div>
@endsection
