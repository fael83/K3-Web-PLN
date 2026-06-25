@extends('layouts.admin')
@section('title', 'Pengguna per Departemen')

@php
    $roleBadge = [
        'sys_admin'       => 'danger',
        'k3_manager'      => 'primary',
        'k3_officer'      => 'info',
        'department_head' => 'warning',
        'employee'        => 'secondary',
        'auditor'         => 'success',
        'viewer'          => 'light text-dark',
    ];
@endphp

@section('content')
@include('admin.partials._header', [
    'heading'    => 'Pengguna per Departemen',
    'subheading' => 'Daftar anggota tiap departemen — digunakan untuk assign form dan koordinasi K3.',
])

{{-- Filter departemen --}}
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body py-2">
        <form method="GET" class="row g-2 align-items-center">
            <div class="col-md-4">
                <select name="department_id" class="form-select form-select-sm">
                    <option value="">— Semua Departemen —</option>
                    @foreach($allDepartments as $d)
                        <option value="{{ $d->id }}" {{ $selectedDept == $d->id ? 'selected' : '' }}>
                            {{ $d->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-auto d-flex gap-2">
                <button class="btn btn-primary btn-sm"><i class="bi bi-funnel me-1"></i>Filter</button>
                <a href="{{ route('admin.users.by-department') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-x"></i> Reset
                </a>
            </div>
            <div class="col-auto ms-auto text-muted small">
                {{ $departments->sum(fn($d) => $d->users->count()) }} pengguna
                · {{ $departments->count() }} departemen ditampilkan
            </div>
        </form>
    </div>
</div>

@forelse($departments as $dept)
<div class="card border-0 shadow-sm mb-3">
    <div class="card-header bg-transparent d-flex align-items-center justify-content-between py-3">
        <div>
            <span class="fw-bold">{{ $dept->name }}</span>
            @if($dept->division)
                <span class="text-muted small ms-2">· {{ $dept->division->name }}</span>
            @endif
        </div>
        <span class="badge bg-primary-subtle text-primary-emphasis">
            {{ $dept->users->count() }} anggota
        </span>
    </div>

    @if($dept->users->isEmpty())
    <div class="card-body text-muted small py-3 text-center">
        <i class="bi bi-person-x me-1"></i> Belum ada pengguna di departemen ini.
    </div>
    @else
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Nama</th>
                    <th>Role</th>
                    <th class="d-none d-md-table-cell">Jabatan</th>
                    <th class="d-none d-lg-table-cell">Unit Kerja</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($dept->users as $u)
                <tr>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <span class="d-flex align-items-center justify-content-center bg-primary-subtle text-primary fw-bold"
                                  style="width:32px;height:32px;border-radius:50%;font-size:.8rem;flex-shrink:0;">
                                {{ strtoupper(substr($u->name, 0, 1)) }}
                            </span>
                            <div>
                                <div class="fw-semibold small">{{ $u->name }}</div>
                                <div class="text-muted" style="font-size:.72rem;">{{ $u->email }}</div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <span class="badge bg-{{ $roleBadge[$u->role] ?? 'secondary' }}-subtle
                                           text-{{ ($roleBadge[$u->role] ?? 'secondary') === 'light text-dark' ? 'dark' : (($roleBadge[$u->role] ?? 'secondary').'-emphasis') }}">
                            {{ \App\Models\User::ROLES[$u->role] ?? $u->role }}
                        </span>
                    </td>
                    <td class="small text-muted d-none d-md-table-cell">{{ $u->position ?? '—' }}</td>
                    <td class="small text-muted d-none d-lg-table-cell">{{ $u->workUnit?->name ?? '—' }}</td>
                    <td>
                        @if($u->is_active)
                            <span class="badge bg-success-subtle text-success-emphasis">
                                <i class="bi bi-circle-fill me-1" style="font-size:.4rem;"></i>Aktif
                            </span>
                        @else
                            <span class="badge bg-danger-subtle text-danger-emphasis">
                                <i class="bi bi-circle-fill me-1" style="font-size:.4rem;"></i>Nonaktif
                            </span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif
</div>
@empty
<div class="text-center text-muted py-5">
    <i class="bi bi-building display-4 d-block mb-2"></i>
    Belum ada departemen.
</div>
@endforelse
@endsection