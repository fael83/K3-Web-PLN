@extends('layouts.admin')
@section('title', 'Audit Log')

@php
    $actionClass = [
        'create' => 'bg-success-subtle text-success-emphasis',
        'update' => 'bg-warning-subtle text-warning-emphasis',
        'delete' => 'bg-danger-subtle text-danger-emphasis',
    ];
@endphp

@section('content')
@include('admin.partials._header', [
    'heading'    => 'Audit Log',
    'subheading' => 'Jejak aktivitas create, update, dan delete pada sistem.',
])

<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th style="width:160px;">Waktu</th>
                    <th>Pengguna</th>
                    <th class="d-none d-md-table-cell">Modul</th>
                    <th style="width:100px;">Aksi</th>
                    <th class="d-none d-lg-table-cell">Keterangan</th>
                    <th class="d-none d-lg-table-cell" style="width:120px;">IP</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($logs as $log)
                    <tr>
                        <td class="small">{{ $log->created_at?->format('d M Y H:i') }}</td>
                        <td>
                            <div class="fw-semibold">{{ $log->user->name ?? 'Sistem' }}</div>
                            <div class="text-muted small">{{ $log->user->email ?? '—' }}</div>
                        </td>
                        <td class="d-none d-md-table-cell">{{ $log->module }}</td>
                        <td><span class="badge {{ $actionClass[$log->action] ?? 'bg-secondary-subtle' }}">{{ ucfirst($log->action) }}</span></td>
                        <td class="d-none d-lg-table-cell text-muted small">{{ $log->description }}</td>
                        <td class="d-none d-lg-table-cell text-muted small">{{ $log->ip_address }}</td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center text-muted py-4">Belum ada aktivitas tercatat.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-3">{{ $logs->links() }}</div>
@endsection
