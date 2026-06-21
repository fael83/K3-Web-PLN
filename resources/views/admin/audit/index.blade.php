@extends('layouts.admin')
@section('title', 'Audit Trail')

@php
    $actionClass = [
        'create' => 'bg-success-subtle text-success-emphasis',
        'update' => 'bg-warning-subtle text-warning-emphasis',
        'delete' => 'bg-danger-subtle text-danger-emphasis',
        'export' => 'bg-info-subtle text-info-emphasis',
        'login'  => 'bg-primary-subtle text-primary-emphasis',
        'logout' => 'bg-secondary-subtle text-secondary-emphasis',
    ];
@endphp

@section('content')
@include('admin.partials._header', [
    'heading'    => 'Audit Trail',
    'subheading' => 'Rekaman seluruh aktivitas pengguna di sistem — read-only, tidak dapat diubah.',
])

{{-- Filter --}}
<div class="card border-0 shadow-sm mb-3">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.audit.index') }}" class="row g-2 align-items-end">
            <div class="col-md-2">
                <label class="form-label small fw-semibold">Dari Tanggal</label>
                <input type="date" name="date_from" class="form-control form-control-sm"
                       value="{{ request('date_from') }}">
            </div>
            <div class="col-md-2">
                <label class="form-label small fw-semibold">Sampai Tanggal</label>
                <input type="date" name="date_to" class="form-control form-control-sm"
                       value="{{ request('date_to') }}">
            </div>
            <div class="col-md-2">
                <label class="form-label small fw-semibold">Pengguna</label>
                <select name="user_id" class="form-select form-select-sm">
                    <option value="">Semua Pengguna</option>
                    @foreach($users as $u)
                        <option value="{{ $u->id }}" {{ request('user_id') == $u->id ? 'selected' : '' }}>
                            {{ $u->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label small fw-semibold">Modul</label>
                <select name="module" class="form-select form-select-sm">
                    <option value="">Semua Modul</option>
                    @foreach($modules as $mod)
                        <option value="{{ $mod }}" {{ request('module') === $mod ? 'selected' : '' }}>
                            {{ $mod }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label small fw-semibold">Aksi</label>
                <select name="action" class="form-select form-select-sm">
                    <option value="">Semua Aksi</option>
                    @foreach(['create','update','delete','export','login','logout'] as $act)
                        <option value="{{ $act }}" {{ request('action') === $act ? 'selected' : '' }}>
                            {{ ucfirst($act) }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2 d-flex gap-2">
                <button type="submit" class="btn btn-primary btn-sm flex-grow-1">
                    <i class="bi bi-funnel me-1"></i> Filter
                </button>
                <a href="{{ route('admin.audit.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-x"></i>
                </a>
            </div>
        </form>
    </div>
</div>

{{-- Tabel --}}
<div class="card border-0 shadow-sm">
    <div class="card-header bg-transparent border-0 d-flex align-items-center justify-content-between py-3">
        <span class="fw-semibold">
            <i class="bi bi-clock-history text-primary me-2"></i>
            Riwayat Aktivitas
            <span class="badge bg-secondary-subtle text-secondary-emphasis ms-1">{{ $logs->total() }} rekaman</span>
        </span>
        <span class="badge bg-danger-subtle text-danger-emphasis">
            <i class="bi bi-lock me-1"></i> Read-Only
        </span>
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th style="width:160px;">Waktu</th>
                    <th>Pengguna</th>
                    <th class="d-none d-md-table-cell">Modul</th>
                    <th style="width:100px;">Aksi</th>
                    <th class="d-none d-lg-table-cell">Keterangan</th>
                    <th class="d-none d-lg-table-cell" style="width:120px;">IP Address</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($logs as $log)
                    <tr>
                        <td class="small text-muted">
                            <i class="bi bi-calendar3 me-1"></i>
                            {{ $log->created_at?->format('d M Y') }}<br>
                            <span class="text-primary">{{ $log->created_at?->format('H:i:s') }}</span>
                        </td>
                        <td>
                            <div class="fw-semibold">{{ $log->user->name ?? 'Sistem' }}</div>
                            <div class="text-muted small">{{ $log->user->role ?? '—' }}</div>
                        </td>
                        <td class="d-none d-md-table-cell">
                            <span class="badge bg-light text-dark border">{{ $log->module }}</span>
                        </td>
                        <td>
                            <span class="badge rounded-pill {{ $actionClass[$log->action] ?? 'bg-secondary-subtle text-secondary-emphasis' }}">
                                {{ ucfirst($log->action) }}
                            </span>
                        </td>
                        <td class="d-none d-lg-table-cell text-muted small">{{ $log->description }}</td>
                        <td class="d-none d-lg-table-cell text-muted small font-monospace">{{ $log->ip_address }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-5">
                            <i class="bi bi-inbox display-6 d-block mb-2"></i>
                            Belum ada aktivitas tercatat.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-3">{{ $logs->links() }}</div>
@endsection
