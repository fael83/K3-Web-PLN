@extends('layouts.admin')
@section('title', 'Manajemen Pengguna')

@php
    $roleBadge = [
        'sys_admin'       => 'danger',
        'k3_manager'      => 'primary',
        'k3_officer'      => 'info',
        'department_head' => 'warning',
        'employee'        => 'secondary',
        'auditor'         => 'success',
        'viewer'          => 'light',
    ];
@endphp

@section('content')
@include('admin.partials._header', [
    'heading'    => 'Manajemen Pengguna',
    'subheading' => 'Kelola akun pengguna, peran, dan penugasan departemen.',
])

{{-- Filter & Aksi --}}
<div class="card border-0 shadow-sm mb-3">
    <div class="card-body">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-3">
                <input type="text" name="search" class="form-control form-control-sm"
                       placeholder="Cari nama / email / NIP..."
                       value="{{ request('search') }}">
            </div>
            <div class="col-md-2">
                <select name="role" class="form-select form-select-sm">
                    <option value="">Semua Role</option>
                    @foreach(\App\Models\User::ROLES as $val => $label)
                        <option value="{{ $val }}" {{ request('role') === $val ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <select name="department_id" class="form-select form-select-sm">
                    <option value="">Semua Departemen</option>
                    @foreach($departments as $dept)
                        <option value="{{ $dept->id }}" {{ request('department_id') == $dept->id ? 'selected' : '' }}>
                            {{ $dept->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <select name="status" class="form-select form-select-sm">
                    <option value="">Semua Status</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Aktif</option>
                    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Nonaktif</option>
                </select>
            </div>
            <div class="col-md-2 d-flex gap-2">
                <button type="submit" class="btn btn-primary btn-sm flex-grow-1">
                    <i class="bi bi-funnel me-1"></i> Filter
                </button>
                <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-x"></i>
                </a>
            </div>
        </form>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-header bg-transparent d-flex justify-content-between align-items-center py-3">
        <span class="fw-semibold">
            <i class="bi bi-people text-primary me-2"></i>
            Daftar Pengguna
            <span class="badge bg-secondary-subtle text-secondary-emphasis ms-1">{{ $users->total() }}</span>
        </span>
        <a href="{{ route('admin.users.create') }}" class="btn btn-primary btn-sm">
            <i class="bi bi-person-plus me-1"></i> Tambah Pengguna
        </a>
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Pengguna</th>
                    <th class="d-none d-md-table-cell">NIP / ID</th>
                    <th class="d-none d-lg-table-cell">Departemen</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th class="d-none d-lg-table-cell">Login Terakhir</th>
                    <th style="width:100px;"></th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                <tr>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <span class="card-icon bg-primary-subtle text-primary"
                                  style="width:36px;height:36px;font-size:.85rem;border-radius:50%;">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </span>
                            <div>
                                <div class="fw-semibold small">{{ $user->name }}</div>
                                <div class="text-muted" style="font-size:.75rem;">{{ $user->email }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="small text-muted d-none d-md-table-cell">{{ $user->employee_id ?? '—' }}</td>
                    <td class="small d-none d-lg-table-cell">
                        {{ $user->department->name ?? '—' }}
                        @if($user->department?->division)
                            <div class="text-muted" style="font-size:.72rem;">{{ $user->department->division->name }}</div>
                        @endif
                    </td>
                    <td>
                        <span class="badge bg-{{ $roleBadge[$user->role] ?? 'secondary' }}-subtle text-{{ $roleBadge[$user->role] ?? 'secondary' }}-emphasis">
                            {{ \App\Models\User::ROLES[$user->role] ?? $user->role }}
                        </span>
                    </td>
                    <td>
                        @if($user->is_active)
                            <span class="badge bg-success-subtle text-success-emphasis">
                                <i class="bi bi-circle-fill me-1" style="font-size:.45rem;"></i>Aktif
                            </span>
                        @else
                            <span class="badge bg-danger-subtle text-danger-emphasis">
                                <i class="bi bi-circle-fill me-1" style="font-size:.45rem;"></i>Nonaktif
                            </span>
                        @endif
                    </td>
                    <td class="small text-muted d-none d-lg-table-cell">
                        {{ $user->last_login_at?->diffForHumans() ?? 'Belum pernah' }}
                    </td>
                    <td>
                        <div class="d-flex gap-1">
                            <a href="{{ route('admin.users.show', $user) }}"
                               class="btn btn-sm btn-outline-primary" title="Detail">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="{{ route('admin.users.edit', $user) }}"
                               class="btn btn-sm btn-outline-secondary" title="Edit">
                                <i class="bi bi-pencil"></i>
                            </a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center text-muted py-5">
                        <i class="bi bi-people display-5 d-block mb-2"></i>
                        Belum ada pengguna.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
<div class="mt-3">{{ $users->links() }}</div>
@endsection
