<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Evidence Package — {{ $audit->title }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        @media print {
            .no-print { display: none !important; }
            body { font-size: 12px; }
        }
        body { font-family: 'Segoe UI', sans-serif; background: #fff; }
        .section-title { border-left: 4px solid #0d6efd; padding-left: .75rem; margin: 1.5rem 0 .75rem; font-weight: 700; }
        .status-badge { font-size: .72rem; padding: .25em .6em; border-radius: 20px; }
        .conformance { background: #d1e7dd; color: #0a3622; }
        .minor_nc    { background: #fff3cd; color: #664d03; }
        .major_nc    { background: #f8d7da; color: #58151c; }
        .observation { background: #cff4fc; color: #055160; }
        .pending     { background: #e9ecef; color: #495057; }
        header { background: #0d3a5c; color: white; padding: 1.5rem; margin-bottom: 1.5rem; border-radius: 8px; }
    </style>
</head>
<body class="p-4">

<header>
    <div class="d-flex justify-content-between align-items-start">
        <div>
            <h4 class="mb-1">Evidence Package — Audit K3</h4>
            <h5 class="mb-0 fw-light">{{ $audit->title }}</h5>
        </div>
        <div class="text-end small">
            <div>Tipe: <strong>{{ \App\Models\AuditChecklist::TYPES[$audit->audit_type] }}</strong></div>
            <div>Tanggal Audit: <strong>{{ $audit->audit_date?->format('d M Y') ?? '—' }}</strong></div>
            <div>Auditor: <strong>{{ $audit->auditor_name ?? '—' }}</strong></div>
            <div>Digenerate: <strong>{{ now()->format('d M Y H:i') }}</strong></div>
            @if($request->date_from || $request->date_to)
                <div>Periode Bukti:
                    <strong>
                        {{ $request->date_from ? \Carbon\Carbon::parse($request->date_from)->format('d M Y') : 'Awal' }}
                        s.d.
                        {{ $request->date_to ? \Carbon\Carbon::parse($request->date_to)->format('d M Y') : 'Sekarang' }}
                    </strong>
                </div>
            @endif
        </div>
    </div>
</header>

<div class="no-print mb-3 d-flex gap-2">
    <button onclick="window.print()" class="btn btn-primary">
        <i class="bi bi-printer me-1"></i> Cetak / Save PDF
    </button>
    <a href="{{ route('admin.audit-evidence.index') }}" class="btn btn-outline-secondary">Kembali</a>
</div>

{{-- BAGIAN 1: Ringkasan Checklist --}}
<h5 class="section-title">1. Ringkasan Hasil Audit Checklist</h5>

@php
    $total    = $audit->items->count();
    $conform  = $audit->items->where('status','conformance')->count();
    $minor    = $audit->items->where('status','minor_nc')->count();
    $major    = $audit->items->where('status','major_nc')->count();
    $obs      = $audit->items->where('status','observation')->count();
    $pending  = $audit->items->where('status','pending')->count();
@endphp

<div class="row g-2 mb-3">
    <div class="col"><div class="border rounded text-center p-2"><div class="h4 fw-bold text-success">{{ $conform }}</div><div class="small">Conformance</div></div></div>
    <div class="col"><div class="border rounded text-center p-2"><div class="h4 fw-bold text-warning">{{ $minor }}</div><div class="small">Minor NC</div></div></div>
    <div class="col"><div class="border rounded text-center p-2"><div class="h4 fw-bold text-danger">{{ $major }}</div><div class="small">Major NC</div></div></div>
    <div class="col"><div class="border rounded text-center p-2"><div class="h4 fw-bold text-info">{{ $obs }}</div><div class="small">Observation</div></div></div>
    <div class="col"><div class="border rounded text-center p-2"><div class="h4 fw-bold text-secondary">{{ $pending }}</div><div class="small">Belum Dinilai</div></div></div>
    <div class="col"><div class="border rounded text-center p-2"><div class="h4 fw-bold">{{ $total }}</div><div class="small">Total Item</div></div></div>
</div>

<table class="table table-sm table-bordered">
    <thead class="table-light">
        <tr>
            <th>#</th>
            <th>Item Pemeriksaan</th>
            <th style="width:130px">Status</th>
            <th>Temuan</th>
            <th>Tindakan Perbaikan</th>
            <th>Bukti</th>
        </tr>
    </thead>
    <tbody>
        @foreach($audit->items as $i => $item)
        <tr>
            <td>{{ $i + 1 }}</td>
            <td>{{ $item->item_name }}</td>
            <td><span class="status-badge {{ $item->status }}">{{ \App\Models\AuditItem::STATUSES[$item->status] }}</span></td>
            <td class="small">{{ $item->finding ?? '—' }}</td>
            <td class="small">{{ $item->corrective_action ?? '—' }}</td>
            <td class="small">{{ $item->evidence_ref ?? '—' }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

{{-- BAGIAN 2: Laporan Insiden --}}
<h5 class="section-title">2. Rekap Insiden pada Periode Ini</h5>
@if($incidents->isNotEmpty())
<table class="table table-sm table-bordered">
    <thead class="table-light">
        <tr>
            <th>#</th>
            <th>Judul Insiden</th>
            <th>Tipe</th>
            <th>Tanggal</th>
            <th>Lokasi</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        @foreach($incidents as $i => $inc)
        <tr>
            <td>{{ $i + 1 }}</td>
            <td>{{ $inc->title }}</td>
            <td class="small">{{ \App\Models\Incident::TYPES[$inc->incident_type] ?? $inc->incident_type }}</td>
            <td class="small">{{ $inc->incident_date?->format('d M Y') }}</td>
            <td class="small">{{ $inc->location ?? '—' }}</td>
            <td><span class="badge bg-secondary">{{ \App\Models\Incident::STATUSES[$inc->status] ?? $inc->status }}</span></td>
        </tr>
        @endforeach
    </tbody>
</table>
@else
<p class="text-muted small">Tidak ada insiden tercatat pada periode ini.</p>
@endif

{{-- BAGIAN 3: Audit Trail --}}
<h5 class="section-title">3. Audit Trail (Log Aktivitas Sistem)</h5>
@if($logs->isNotEmpty())
<table class="table table-sm table-bordered">
    <thead class="table-light">
        <tr>
            <th>Waktu</th>
            <th>Pengguna</th>
            <th>Modul</th>
            <th>Aksi</th>
            <th>Keterangan</th>
        </tr>
    </thead>
    <tbody>
        @foreach($logs as $log)
        <tr>
            <td class="small">{{ $log->created_at?->format('d M Y H:i') }}</td>
            <td class="small">{{ $log->user->name ?? 'Sistem' }}</td>
            <td class="small">{{ $log->module }}</td>
            <td class="small">{{ ucfirst($log->action) }}</td>
            <td class="small">{{ $log->description }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
@else
<p class="text-muted small">Tidak ada log aktivitas pada periode ini.</p>
@endif

{{-- Footer --}}
<hr>
<div class="row small text-muted mt-3">
    <div class="col-6">
        <strong>Dibuat oleh:</strong> Sistem K3 PLN<br>
        <strong>Tanggal:</strong> {{ now()->format('d M Y H:i') }}
    </div>
    <div class="col-6 text-end">
        <div class="mt-4">Tanda Tangan Auditor</div>
        <div style="border-bottom:1px solid #333;width:150px;margin-left:auto;margin-top:40px;"></div>
        <div>{{ $audit->auditor_name ?? '____________________' }}</div>
    </div>
</div>

<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
</body>
</html>
