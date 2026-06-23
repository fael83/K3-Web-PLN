@extends('layouts.admin')
@section('title', 'Detail Pengguna: ' . $user->name)

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
    $actionClass = [
        'create' => 'bg-success-subtle text-success-emphasis',
        'update' => 'bg-warning-subtle text-warning-emphasis',
        'delete' => 'bg-danger-subtle text-danger-emphasis',
        'login'  => 'bg-primary-subtle text-primary-emphasis',
        'logout' => 'bg-secondary-subtle text-secondary-emphasis',
        'export' => 'bg-info-subtle text-info-emphasis',
    ];
@endphp

@section('content')
@include('admin.partials._header', [
    'heading'    => $user->name,
    'subheading' => (\App\Models\User::ROLES[$user->role] ?? $user->role) . ' · ' . ($user->department->name ?? 'Tanpa Departemen'),
])

<div class="row g-3">
    {{-- Kolom kiri: Info --}}
    <div class="col-md-4">
        {{-- Profil --}}
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-body text-center py-4">
                <div class="mx-auto mb-3 d-flex align-items-center justify-content-center bg-primary-subtle text-primary fw-bold"
                     style="width:72px;height:72px;border-radius:50%;font-size:2rem;">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
                <h5 class="fw-bold mb-1">{{ $user->name }}</h5>
                <div class="text-muted small mb-2">{{ $user->email }}</div>
                <span class="badge bg-{{ $roleBadge[$user->role] ?? 'secondary' }}-subtle text-{{ $roleBadge[$user->role] ?? 'secondary' }}-emphasis mb-2">
                    {{ \App\Models\User::ROLES[$user->role] ?? $user->role }}
                </span>
                <div>
                    @if($user->is_active)
                        <span class="badge bg-success-subtle text-success-emphasis">● Aktif</span>
                    @else
                        <span class="badge bg-danger-subtle text-danger-emphasis">● Nonaktif</span>
                    @endif
                </div>
            </div>
        </div>

        {{-- Detail --}}
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-header bg-transparent fw-semibold small">Info Pengguna</div>
            <div class="card-body small">
                <dl class="row mb-0">
                    <dt class="col-5 text-muted">NIP</dt>
                    <dd class="col-7">{{ $user->employee_id ?? '—' }}</dd>
                    <dt class="col-5 text-muted">Jabatan</dt>
                    <dd class="col-7">{{ $user->position ?? '—' }}</dd>
                    <dt class="col-5 text-muted">No. HP</dt>
                    <dd class="col-7">{{ $user->phone ?? '—' }}</dd>
                    <dt class="col-5 text-muted">Divisi</dt>
                    <dd class="col-7">{{ $user->department?->division?->name ?? '—' }}</dd>
                    <dt class="col-5 text-muted">Departemen</dt>
                    <dd class="col-7">{{ $user->department?->name ?? '—' }}</dd>
                    <dt class="col-5 text-muted">Unit Kerja</dt>
                    <dd class="col-7">{{ $user->workUnit?->name ?? '—' }}</dd>
                    <dt class="col-5 text-muted">Bergabung</dt>
                    <dd class="col-7">{{ $user->created_at?->format('d M Y') }}</dd>
                    <dt class="col-5 text-muted">Login Terakhir</dt>
                    <dd class="col-7">{{ $user->last_login_at?->format('d M Y H:i') ?? 'Belum pernah' }}</dd>
                </dl>
            </div>
        </div>

        {{-- Aksi --}}
        <div class="d-grid gap-2">
            <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-primary btn-sm">
                <i class="bi bi-pencil me-1"></i> Edit Data
            </a>

            {{-- Toggle Aktif --}}
            <form method="POST" action="{{ route('admin.users.toggle-active', $user) }}">
                @csrf @method('PATCH')
                <button class="btn btn-sm w-100 {{ $user->is_active ? 'btn-outline-warning' : 'btn-outline-success' }}">
                    <i class="bi bi-{{ $user->is_active ? 'person-dash' : 'person-check' }} me-1"></i>
                    {{ $user->is_active ? 'Nonaktifkan Akun' : 'Aktifkan Akun' }}
                </button>
            </form>

            {{-- Reset Password --}}
            <button class="btn btn-outline-secondary btn-sm" data-bs-toggle="collapse"
                    data-bs-target="#resetPwForm">
                <i class="bi bi-key me-1"></i> Reset Password
            </button>
            <div class="collapse" id="resetPwForm">
                <form method="POST" action="{{ route('admin.users.reset-password', $user) }}"
                      class="border rounded p-3 bg-light-subtle">
                    @csrf @method('PATCH')
                    <div class="mb-2">
                        <input type="password" name="password" class="form-control form-control-sm"
                               placeholder="Password baru (min. 8)" required>
                    </div>
                    <div class="mb-2">
                        <input type="password" name="password_confirmation" class="form-control form-control-sm"
                               placeholder="Konfirmasi password" required>
                    </div>
                    <button class="btn btn-warning btn-sm w-100">Simpan Password Baru</button>
                </form>
            </div>

            {{-- Hapus --}}
            @if($user->id !== auth()->id())
            <form method="POST" action="{{ route('admin.users.destroy', $user) }}"
                  onsubmit="return confirm('Yakin hapus akun {{ $user->name }}?')">
                @csrf @method('DELETE')
                <button class="btn btn-outline-danger btn-sm w-100">
                    <i class="bi bi-trash me-1"></i> Hapus Akun
                </button>
            </form>
            @endif

            <a href="{{ route('admin.users.index') }}" class="btn btn-light btn-sm">
                <i class="bi bi-arrow-left me-1"></i> Kembali
            </a>
        </div>
    </div>

    {{-- Kolom kanan: Activity Log --}}
    <div class="col-md-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-transparent fw-semibold d-flex justify-content-between">
                <span><i class="bi bi-clock-history me-2 text-primary"></i>Riwayat Aktivitas</span>
                <span class="badge bg-secondary-subtle text-secondary-emphasis">{{ $activityLogs->total() }} rekaman</span>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th style="width:150px;">Waktu</th>
                            <th style="width:90px;">Modul</th>
                            <th style="width:80px;">Aksi</th>
                            <th>Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($activityLogs as $log)
                        <tr>
                            <td class="small text-muted">
                                {{ $log->created_at?->format('d M Y') }}<br>
                                <span class="text-primary">{{ $log->created_at?->format('H:i:s') }}</span>
                            </td>
                            <td>
                                <span class="badge bg-light text-dark border small">{{ $log->module }}</span>
                            </td>
                            <td>
                                <span class="badge rounded-pill {{ $actionClass[$log->action] ?? 'bg-secondary-subtle text-secondary-emphasis' }}">
                                    {{ ucfirst($log->action) }}
                                </span>
                            </td>
                            <td class="small text-muted">{{ $log->description }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted py-5">
                                <i class="bi bi-inbox display-6 d-block mb-2"></i>
                                Belum ada aktivitas tercatat.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="mt-3">{{ $activityLogs->links() }}</div>
    </div>
</div>
@endsection
