@extends('layouts.admin')
@section('title', 'Dashboard')

@push('styles')
<style>
/* ── Role badge header ────────────────────────────── */
.role-badge {
    display: inline-flex;
    align-items: center;
    gap: .4rem;
    padding: .3rem .75rem;
    border-radius: 2rem;
    font-size: .72rem;
    font-weight: 600;
    letter-spacing: .02em;
}
.role-sys_admin    { background: #e8f0ff; color: #14489A; }
.role-k3_manager   { background: #fff3e0; color: #e65100; }
.role-k3_officer   { background: #e3f2fd; color: #0277bd; }
.role-department_head { background: #f3e5f5; color: #6a1b9a; }
.role-auditor      { background: #e8f5e9; color: #2e7d32; }
.role-viewer       { background: #fafafa; color: #546e7a; border: 1px solid #cfd8dc; }

/* ── Stat tiles ───────────────────────────────────── */
.stat-tile {
    background: #fff;
    border-radius: 14px;
    border: 1px solid #e8ecf0;
    transition: box-shadow .15s;
}
.stat-tile:hover { box-shadow: 0 4px 18px rgba(0,0,0,.07); }

/* ── Card panels ──────────────────────────────────── */
.card-panel {
    background: #fff;
    border-radius: 14px;
    border: 1px solid #e8ecf0;
}

/* ── Read-only ribbon for viewer/auditor ──────────── */
.readonly-ribbon {
    background: #e8f5e9;
    border: 1px solid #c8e6c9;
    color: #2e7d32;
    border-radius: 8px;
    padding: .4rem .9rem;
    font-size: .8rem;
    display: flex;
    align-items: center;
    gap: .4rem;
}

/* ── Dept scope banner ────────────────────────────── */
.scope-banner {
    background: linear-gradient(135deg, #f3e5f5, #ede7f6);
    border: 1px solid #ce93d8;
    border-radius: 10px;
    padding: .55rem 1rem;
    color: #4a148c;
    font-size: .82rem;
    display: flex;
    align-items: center;
    gap: .5rem;
}

/* ── Quick action buttons ─────────────────────────── */
.quick-action {
    display: flex;
    align-items: center;
    gap: .75rem;
    padding: .7rem 1rem;
    border-radius: 10px;
    border: 1px solid #e8ecf0;
    background: #fff;
    color: #344054;
    text-decoration: none;
    font-size: .85rem;
    font-weight: 500;
    transition: all .15s;
}
.quick-action:hover {
    background: #f0f7ff;
    border-color: #009EE3;
    color: #14489A;
    transform: translateX(2px);
}
.quick-action .qa-icon {
    width: 36px; height: 36px;
    border-radius: 9px;
    display: flex; align-items: center; justify-content: center;
    font-size: 1rem;
    flex-shrink: 0;
}

/* ── Section header ───────────────────────────────── */
.section-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 1rem;
}
.section-header h6 { margin: 0; font-weight: 700; }
</style>
@endpush

@section('content')

{{-- ══════════════════════════════════════════════════════════
     HEADER BARIS: Judul + Role Badge + Scope Info + export buttons + filter form
══════════════════════════════════════════════════════════ --}}
<div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-4">
    <div>
        <h5 class="fw-bold mb-1">Dashboard K3</h5>
        <div class="text-muted small">
            Selamat datang, <strong>{{ auth()->user()->name }}</strong>
            @if(auth()->user()->department)
                · {{ auth()->user()->department->name }}
            @endif
        </div>
    </div>
    <div class="d-flex align-items-center gap-2 flex-wrap">
        <span class="role-badge role-{{ $role }}">
            <i class="bi bi-person-badge"></i>
            {{ \App\Models\User::ROLES[$role] ?? $role }}
        </span>
        @if(in_array($role, ['auditor','viewer']))
        <div class="readonly-ribbon">
            <i class="bi bi-eye"></i> Mode Baca Saja
        </div>
        @endif
    </div>
</div>

{{-- Dept Head scope banner --}}
@if($role === 'department_head' && auth()->user()->department)
<div class="scope-banner mb-4">
    <i class="bi bi-building"></i>
    <span>Menampilkan data seluruh institusi — scope departemen akan aktif setelah field <code>department_id</code> ditambahkan ke tabel incidents.</span>
    <strong class="ms-auto">Departemen: {{ auth()->user()->department->name }}</strong>
</div>
@endif

{{-- Tombol export — hanya untuk role yang diizinkan --}}
@if(in_array($role, ['sys_admin', 'k3_manager', 'k3_officer', 'department_head', 'auditor']))
<div class="d-flex gap-2">
    <a href="{{ route('admin.export.incident.pdf') }}"
       class="btn btn-sm btn-outline-danger"
       title="Export PDF">
        <i class="bi bi-file-earmark-pdf me-1"></i>PDF
    </a>
    <a href="{{ route('admin.export.incident.excel') }}"
       class="btn btn-sm btn-outline-success"
       title="Export Excel">
        <i class="bi bi-file-earmark-excel me-1"></i>Excel
    </a>
    @if(in_array($role, ['sys_admin', 'k3_manager', 'auditor']))
    <a href="{{ route('admin.export.dashboard.pdf') }}"
       class="btn btn-sm btn-outline-primary"
       title="Ringkasan PDF">
        <i class="bi bi-bar-chart-line me-1"></i>Ringkasan
    </a>
    @endif
</div>
@endif

@if($canFilter)
<div class="card-panel p-3 mb-4">
    <form method="GET" action="{{ route('admin.dashboard') }}" class="row g-2 align-items-end">
        <div class="col-md-3">
            <label class="form-label small fw-semibold mb-1">Departemen</label>
            <select name="dept" class="form-select form-select-sm">
                <option value="">Semua Departemen</option>
                @foreach(\App\Models\Department::orderBy('name')->get() as $dept)
                    <option value="{{ $dept->id }}"
                        {{ ($filters['filterDept'] ?? '') == $dept->id ? 'selected' : '' }}>
                        {{ $dept->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <label class="form-label small fw-semibold mb-1">Dari Tanggal</label>
            <input type="date" name="from" class="form-control form-control-sm"
                   value="{{ $filters['filterFrom'] ?? '' }}">
        </div>
        <div class="col-md-2">
            <label class="form-label small fw-semibold mb-1">Sampai Tanggal</label>
            <input type="date" name="to" class="form-control form-control-sm"
                   value="{{ $filters['filterTo'] ?? '' }}">
        </div>
        <div class="col-md-2">
            <label class="form-label small fw-semibold mb-1">Status Insiden</label>
            <select name="status" class="form-select form-select-sm">
                <option value="">Semua Status</option>
                @foreach(\App\Models\Incident::STATUSES as $key => $label)
                    <option value="{{ $key }}"
                        {{ ($filters['filterStatus'] ?? '') === $key ? 'selected' : '' }}>
                        {{ $label }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3 d-flex gap-2">
            <button type="submit" class="btn btn-primary btn-sm">
                <i class="bi bi-funnel me-1"></i>Filter
            </button>
            <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-x-circle me-1"></i>Reset
            </a>
        </div>
    </form>

    {{-- Tampilkan label filter aktif --}}
    @if(array_filter($filters))
    <div class="mt-2 d-flex flex-wrap gap-1">
        <span class="badge bg-primary-subtle text-primary-emphasis small">Filter aktif:</span>
        @if($filters['filterDept'])
            <span class="badge bg-secondary-subtle text-secondary-emphasis small">
                Dept: {{ \App\Models\Department::find($filters['filterDept'])?->name }}
            </span>
        @endif
        @if($filters['filterFrom'])
            <span class="badge bg-secondary-subtle text-secondary-emphasis small">
                Dari: {{ $filters['filterFrom'] }}
            </span>
        @endif
        @if($filters['filterTo'])
            <span class="badge bg-secondary-subtle text-secondary-emphasis small">
                S/d: {{ $filters['filterTo'] }}
            </span>
        @endif
        @if($filters['filterStatus'])
            <span class="badge bg-secondary-subtle text-secondary-emphasis small">
                Status: {{ \App\Models\Incident::STATUSES[$filters['filterStatus']] }}
            </span>
        @endif
    </div>
    @endif
</div>
@endif

{{-- ══════════════════════════════════════════════════════════
     BLOK 1 — KPI CARDS
     Tampil untuk: sys_admin, k3_manager, k3_officer, dept_head, auditor, viewer
══════════════════════════════════════════════════════════ --}}
<div class="row g-3 mb-4">

    {{-- Safe Days --}}
    <div class="col-6 col-md-4 col-xl-2">
        <div class="stat-tile p-3 h-100 text-center">
            <div class="icn mb-2 mx-auto" style="background:rgba(28,122,67,.1);color:#1c7a43;width:44px;height:44px;border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:1.3rem;">
                <i class="bi bi-shield-check"></i>
            </div>
            <div class="h3 fw-bold mb-0 text-success">{{ $safeDays !== null ? $safeDays : '—' }}</div>
            <div class="small text-muted">Hari Tanpa Insiden</div>
        </div>
    </div>

    {{-- Open Incidents --}}
    <div class="col-6 col-md-4 col-xl-2">
        <div class="stat-tile p-3 h-100 text-center">
            <div class="icn mb-2 mx-auto" style="background:rgba(180,35,35,.1);color:#B42323;width:44px;height:44px;border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:1.3rem;">
                <i class="bi bi-exclamation-circle"></i>
            </div>
            <div class="h3 fw-bold mb-0 {{ $openIncidents > 0 ? 'text-danger' : 'text-success' }}">{{ $openIncidents }}</div>
            <div class="small text-muted">Insiden Terbuka</div>
        </div>
    </div>

    {{-- Open CAPA --}}
    <div class="col-6 col-md-4 col-xl-2">
        <div class="stat-tile p-3 h-100 text-center">
            <div class="icn mb-2 mx-auto" style="background:rgba(245,166,35,.14);color:#F5A623;width:44px;height:44px;border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:1.3rem;">
                <i class="bi bi-tools"></i>
            </div>
            <div class="h3 fw-bold mb-0 {{ $openCapa > 0 ? 'text-warning' : 'text-success' }}">{{ $openCapa }}</div>
            <div class="small text-muted">CAPA Belum Selesai</div>
        </div>
    </div>

    {{-- Open Audit Findings --}}
    <div class="col-6 col-md-4 col-xl-2">
        <div class="stat-tile p-3 h-100 text-center">
            <div class="icn mb-2 mx-auto" style="background:rgba(112,72,179,.1);color:#7048b3;width:44px;height:44px;border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:1.3rem;">
                <i class="bi bi-clipboard-check"></i>
            </div>
            <div class="h3 fw-bold mb-0 {{ $openFindings > 0 ? 'text-warning' : 'text-success' }}">{{ $openFindings }}</div>
            <div class="small text-muted">Temuan Audit</div>
        </div>
    </div>

    {{-- Total Insiden --}}
    <div class="col-6 col-md-4 col-xl-2">
        <div class="stat-tile p-3 h-100 text-center">
            <div class="icn mb-2 mx-auto" style="background:rgba(0,158,227,.12);color:#009EE3;width:44px;height:44px;border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:1.3rem;">
                <i class="bi bi-clipboard-data"></i>
            </div>
            <div class="h3 fw-bold mb-0">{{ $counts['incident'] }}</div>
            <div class="small text-muted">Total Insiden</div>
        </div>
    </div>

    {{-- Total Bahaya --}}
    <div class="col-6 col-md-4 col-xl-2">
        <div class="stat-tile p-3 h-100 text-center">
            <div class="icn mb-2 mx-auto" style="background:rgba(245,166,35,.14);color:#F5A623;width:44px;height:44px;border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:1.3rem;">
                <i class="bi bi-exclamation-triangle"></i>
            </div>
            <div class="h3 fw-bold mb-0">{{ $counts['hazard'] }}</div>
            <div class="small text-muted">Potensi Bahaya</div>
        </div>
    </div>
</div>

{{-- ══════════════════════════════════════════════════════════
     BLOK KHUSUS PER ROLE
══════════════════════════════════════════════════════════ --}}

{{-- ────────────────────────────────────────────────────────
     SYS ADMIN: Full dashboard + resource counters + user mgmt links
──────────────────────────────────────────────────────── --}}
@if($role === 'sys_admin')

    {{-- Row resource counts --}}
    <div class="row g-3 mb-4">
        @foreach([
            ['apd',    'bi-shield-shaded',   'APD Terdaftar',   'primary',  route('admin.apd.index')],
            ['sop',    'bi-list-check',       'SOP Langkah',     'info',     route('admin.sop.index')],
            ['hazard', 'bi-exclamation-triangle','Bahaya',       'warning',  route('admin.hazard.index')],
            ['team',   'bi-people',           'Anggota Tim K3',  'success',  route('admin.team.index')],
            ['health', 'bi-heart-pulse',      'Program Kesehatan','danger', route('admin.health.index')],
        ] as [$key, $icon, $label, $color, $link])
        <div class="col-6 col-md-4 col-xl-2">
            <a href="{{ $link }}" class="text-decoration-none">
                <div class="stat-tile p-3 h-100 text-center">
                    <div class="icn mb-2 mx-auto" style="width:40px;height:40px;border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:1.1rem;" class="bg-{{ $color }}-subtle text-{{ $color }}">
                        <i class="bi {{ $icon }} text-{{ $color }}"></i>
                    </div>
                    <div class="h4 fw-bold mb-0 text-{{ $color }}">{{ $counts[$key] }}</div>
                    <div class="small text-muted">{{ $label }}</div>
                </div>
            </a>
        </div>
        @endforeach
        @if($docStats)
        <div class="col-6 col-md-4 col-xl-2">
            <a href="{{ route('admin.documents.index') }}" class="text-decoration-none">
                <div class="stat-tile p-3 h-100 text-center">
                    <div class="icn mb-2 mx-auto" style="width:40px;height:40px;border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:1.1rem;">
                        <i class="bi bi-file-earmark-text text-secondary"></i>
                    </div>
                    <div class="h4 fw-bold mb-0">{{ $docStats['total'] }}</div>
                    <div class="small text-muted">Dokumen</div>
                </div>
            </a>
        </div>
        @endif
    </div>

    {{-- Charts row --}}
    <div class="row g-3 mb-4">
        <div class="col-lg-8">
            <div class="card-panel p-4 h-100">
                <div class="section-header">
                    <h6>Tren Insiden — Tahun Ini vs Tahun Lalu</h6>
                    <span class="badge bg-primary-subtle text-primary-emphasis">12 Bulan</span>
                </div>
                <canvas id="trendChart" height="90"></canvas>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card-panel p-4 h-100">
                <h6 class="fw-bold mb-3">Status Insiden</h6>
                <canvas id="statusChart" height="200"></canvas>
                @include('admin.dashboard._status_legend')
            </div>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-lg-6">
            <div class="card-panel p-4 h-100">
                <h6 class="fw-bold mb-3">Insiden per Tipe</h6>
                <canvas id="typeChart" height="140"></canvas>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card-panel p-4 h-100">
                <h6 class="fw-bold mb-3">Bahaya per Kategori</h6>
                <canvas id="hazardChart" height="140"></canvas>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-lg-4">
            @include('admin.dashboard._audit_summary')
        </div>
        <div class="col-lg-4">
            @include('admin.dashboard._recent_activity')
        </div>
        <div class="col-lg-4">
            <div class="card-panel p-4 h-100">
                <h6 class="fw-bold mb-3"><i class="bi bi-lightning text-warning me-2"></i>Aksi Cepat Admin</h6>
                <div class="d-flex flex-column gap-2">
                    <a href="{{ route('admin.users.index') }}" class="quick-action">
                        <span class="qa-icon" style="background:#e8f0ff;color:#14489A;"><i class="bi bi-people"></i></span>
                        Kelola Pengguna
                    </a>
                    <a href="{{ route('admin.organization.index') }}" class="quick-action">
                        <span class="qa-icon" style="background:#f3e5f5;color:#6a1b9a;"><i class="bi bi-diagram-3"></i></span>
                        Struktur Organisasi
                    </a>
                    <a href="{{ route('admin.audit.index') }}" class="quick-action">
                        <span class="qa-icon" style="background:#e8f5e9;color:#2e7d32;"><i class="bi bi-journal-check"></i></span>
                        Audit Log
                    </a>
                    <a href="{{ route('admin.audit-checklist.index') }}" class="quick-action">
                        <span class="qa-icon" style="background:#fff3e0;color:#e65100;"><i class="bi bi-clipboard2-check"></i></span>
                        Checklist Audit
                    </a>
                    <a href="{{ route('admin.documents.index') }}" class="quick-action">
                        <span class="qa-icon" style="background:#e3f2fd;color:#0277bd;"><i class="bi bi-file-earmark-text"></i></span>
                        Manajemen Dokumen
                    </a>
                </div>
            </div>
        </div>
    </div>

{{-- ────────────────────────────────────────────────────────
     K3 MANAGER: Full institution-wide view + charts + quick actions
──────────────────────────────────────────────────────── --}}
@elseif($role === 'k3_manager')

    <div class="row g-3 mb-4">
        <div class="col-lg-8">
            <div class="card-panel p-4 h-100">
                <div class="section-header">
                    <h6>Tren Insiden — Tahun Ini vs Tahun Lalu</h6>
                    <span class="badge bg-primary-subtle text-primary-emphasis">12 Bulan</span>
                </div>
                <canvas id="trendChart" height="90"></canvas>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card-panel p-4 h-100">
                <h6 class="fw-bold mb-3">Status Insiden</h6>
                <canvas id="statusChart" height="200"></canvas>
                @include('admin.dashboard._status_legend')
            </div>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-lg-6">
            <div class="card-panel p-4 h-100">
                <h6 class="fw-bold mb-3">Insiden per Tipe</h6>
                <canvas id="typeChart" height="140"></canvas>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card-panel p-4 h-100">
                <h6 class="fw-bold mb-3">Bahaya per Kategori</h6>
                <canvas id="hazardChart" height="140"></canvas>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-lg-4">
            @include('admin.dashboard._audit_summary')
        </div>
        <div class="col-lg-4">
            @include('admin.dashboard._recent_activity')
        </div>
        <div class="col-lg-4">
            <div class="card-panel p-4 h-100">
                <h6 class="fw-bold mb-3"><i class="bi bi-lightning text-warning me-2"></i>Aksi Cepat</h6>
                <div class="d-flex flex-column gap-2">
                    <a href="{{ route('admin.incident.create') }}" class="quick-action">
                        <span class="qa-icon" style="background:#fdecea;color:#B42323;"><i class="bi bi-plus-circle"></i></span>
                        Lapor Insiden Baru
                    </a>
                    <a href="{{ route('admin.hazard.index') }}" class="quick-action">
                        <span class="qa-icon" style="background:#fff3e0;color:#e65100;"><i class="bi bi-exclamation-triangle"></i></span>
                        Kelola Bahaya
                    </a>
                    <a href="{{ route('admin.audit-checklist.index') }}" class="quick-action">
                        <span class="qa-icon" style="background:#e8f5e9;color:#2e7d32;"><i class="bi bi-clipboard2-check"></i></span>
                        Checklist Audit
                    </a>
                    <a href="{{ route('admin.documents.index') }}" class="quick-action">
                        <span class="qa-icon" style="background:#e3f2fd;color:#0277bd;"><i class="bi bi-file-earmark-text"></i></span>
                        Dokumen K3
                    </a>
                </div>
                @if($docStats)
                <hr>
                <div class="small text-muted fw-semibold mb-2">Status Dokumen</div>
                <div class="row g-2 text-center">
                    <div class="col-6"><div class="border rounded p-2"><div class="fw-bold text-success">{{ $docStats['approved'] }}</div><div class="small text-muted">Approved</div></div></div>
                    <div class="col-6"><div class="border rounded p-2"><div class="fw-bold text-warning">{{ $docStats['review'] }}</div><div class="small text-muted">Review</div></div></div>
                </div>
                @endif
            </div>
        </div>
    </div>

{{-- ────────────────────────────────────────────────────────
     K3 OFFICER: Operational dashboard
──────────────────────────────────────────────────────── --}}
@elseif($role === 'k3_officer')

    <div class="row g-3 mb-4">
        <div class="col-lg-8">
            <div class="card-panel p-4 h-100">
                <div class="section-header">
                    <h6>Tren Insiden 12 Bulan</h6>
                    <a href="{{ route('admin.incident.index') }}" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
                </div>
                <canvas id="trendChart" height="90"></canvas>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card-panel p-4 h-100">
                <h6 class="fw-bold mb-3">Status Insiden</h6>
                <canvas id="statusChart" height="200"></canvas>
                @include('admin.dashboard._status_legend')
            </div>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-lg-4">
            <div class="card-panel p-4 h-100">
                <h6 class="fw-bold mb-3">Insiden per Tipe</h6>
                <canvas id="typeChart" height="140"></canvas>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card-panel p-4 h-100">
                <h6 class="fw-bold mb-3">Bahaya per Kategori</h6>
                <canvas id="hazardChart" height="140"></canvas>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card-panel p-4 h-100">
                <h6 class="fw-bold mb-3"><i class="bi bi-lightning text-warning me-2"></i>Aksi Cepat Officer</h6>
                <div class="d-flex flex-column gap-2">
                    <a href="{{ route('admin.incident.create') }}" class="quick-action">
                        <span class="qa-icon" style="background:#fdecea;color:#B42323;"><i class="bi bi-plus-circle"></i></span>
                        Lapor Insiden
                    </a>
                    <a href="{{ route('admin.apd.index') }}" class="quick-action">
                        <span class="qa-icon" style="background:#e8ecff;color:#3949ab;"><i class="bi bi-shield-shaded"></i></span>
                        Kelola APD
                    </a>
                    <a href="{{ route('admin.hazard.index') }}" class="quick-action">
                        <span class="qa-icon" style="background:#fff3e0;color:#e65100;"><i class="bi bi-exclamation-triangle"></i></span>
                        Identifikasi Bahaya
                    </a>
                    <a href="{{ route('admin.audit-checklist.index') }}" class="quick-action">
                        <span class="qa-icon" style="background:#e8f5e9;color:#2e7d32;"><i class="bi bi-clipboard2-check"></i></span>
                        Audit Checklist
                    </a>
                    <a href="{{ route('admin.documents.index') }}" class="quick-action">
                        <span class="qa-icon" style="background:#e3f2fd;color:#0277bd;"><i class="bi bi-file-earmark-text"></i></span>
                        Dokumen K3
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-lg-6">
            @include('admin.dashboard._audit_summary')
        </div>
        <div class="col-lg-6">
            @include('admin.dashboard._recent_activity')
        </div>
    </div>

{{-- ────────────────────────────────────────────────────────
     DEPARTMENT HEAD: Dashboard terfokus departemen
──────────────────────────────────────────────────────── --}}
@elseif($role === 'department_head')

    <div class="row g-3 mb-4">
        <div class="col-lg-7">
            <div class="card-panel p-4 h-100">
                <div class="section-header">
                    <h6>Tren Insiden 12 Bulan</h6>
                    <a href="{{ route('admin.incident.index') }}" class="btn btn-sm btn-outline-primary">Lihat Detail</a>
                </div>
                <canvas id="trendChart" height="100"></canvas>
            </div>
        </div>
        <div class="col-lg-5">
            <div class="card-panel p-4 h-100">
                <h6 class="fw-bold mb-3">Insiden per Tipe</h6>
                <canvas id="typeChart" height="160"></canvas>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-lg-5">
            <div class="card-panel p-4 h-100">
                <h6 class="fw-bold mb-3"><i class="bi bi-lightning text-warning me-2"></i>Aksi Departemen</h6>
                <div class="d-flex flex-column gap-2">
                    <a href="{{ route('admin.incident.create') }}" class="quick-action">
                        <span class="qa-icon" style="background:#fdecea;color:#B42323;"><i class="bi bi-plus-circle"></i></span>
                        Lapor Insiden Baru
                    </a>
                    <a href="{{ route('admin.incident.index') }}" class="quick-action">
                        <span class="qa-icon" style="background:#e3f2fd;color:#0277bd;"><i class="bi bi-list-ul"></i></span>
                        Daftar Insiden
                    </a>
                    <a href="{{ route('admin.documents.index') }}" class="quick-action">
                        <span class="qa-icon" style="background:#e8f5e9;color:#2e7d32;"><i class="bi bi-file-earmark-text"></i></span>
                        Lihat Dokumen K3
                    </a>
                </div>

                @if($openCapa > 0)
                <div class="alert alert-warning mt-3 py-2 px-3 small mb-0">
                    <i class="bi bi-tools me-1"></i>
                    <strong>{{ $openCapa }}</strong> CAPA belum ditindaklanjuti.
                    <a href="{{ route('admin.incident.index') }}" class="alert-link">Tinjau →</a>
                </div>
                @endif
            </div>
        </div>
        <div class="col-lg-7">
            <div class="card-panel p-4 h-100">
                <h6 class="fw-bold mb-3"><i class="bi bi-clock-history text-primary me-2"></i>Insiden Terbaru</h6>
                <div class="table-responsive">
                    <table class="table table-sm table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Judul</th>
                                <th>Tipe</th>
                                <th>Tanggal</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentIncidents as $inc)
                            <tr>
                                <td class="small">{{ Str::limit($inc->title, 30) }}</td>
                                <td><span class="badge bg-secondary-subtle text-secondary-emphasis small">{{ $inc->incident_type }}</span></td>
                                <td class="small text-muted">{{ optional($inc->incident_date)->format('d M Y') ?? '-' }}</td>
                                <td>
                                    @php $sc = ['open'=>'danger','investigasi'=>'warning','selesai'=>'success'][$inc->status] ?? 'secondary' @endphp
                                    <span class="badge bg-{{ $sc }}-subtle text-{{ $sc }}-emphasis small">{{ $inc->status }}</span>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="4" class="text-center text-muted small py-3">Belum ada insiden</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

{{-- ────────────────────────────────────────────────────────
     AUDITOR: Read-only, full institution view + audit focus
──────────────────────────────────────────────────────── --}}
@elseif($role === 'auditor')

    <div class="alert alert-success py-2 px-3 small d-flex align-items-center gap-2 mb-4">
        <i class="bi bi-eye fs-5"></i>
        <span>Anda masuk sebagai <strong>Auditor</strong>. Semua tampilan bersifat <strong>baca saja</strong>. Tidak ada tombol edit/hapus yang aktif.</span>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-lg-8">
            <div class="card-panel p-4 h-100">
                <div class="section-header">
                    <h6>Tren Insiden — Tahun Ini vs Tahun Lalu</h6>
                    <span class="badge bg-primary-subtle text-primary-emphasis">12 Bulan</span>
                </div>
                <canvas id="trendChart" height="90"></canvas>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card-panel p-4 h-100">
                <h6 class="fw-bold mb-3">Status Insiden</h6>
                <canvas id="statusChart" height="200"></canvas>
                @include('admin.dashboard._status_legend')
            </div>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-lg-6">
            <div class="card-panel p-4 h-100">
                <h6 class="fw-bold mb-3">Insiden per Tipe</h6>
                <canvas id="typeChart" height="140"></canvas>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card-panel p-4 h-100">
                <h6 class="fw-bold mb-3">Bahaya per Kategori</h6>
                <canvas id="hazardChart" height="140"></canvas>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-lg-5">
            @include('admin.dashboard._audit_summary')
        </div>
        <div class="col-lg-4">
            @include('admin.dashboard._recent_activity')
        </div>
        <div class="col-lg-3">
            <div class="card-panel p-4 h-100">
                <h6 class="fw-bold mb-3"><i class="bi bi-link-45deg text-primary me-2"></i>Navigasi Audit</h6>
                <div class="d-flex flex-column gap-2">
                    <a href="{{ route('admin.audit-checklist.index') }}" class="quick-action">
                        <span class="qa-icon" style="background:#e8f5e9;color:#2e7d32;"><i class="bi bi-clipboard2-check"></i></span>
                        Checklist Audit
                    </a>
                    <a href="{{ route('admin.audit.index') }}" class="quick-action">
                        <span class="qa-icon" style="background:#e3f2fd;color:#0277bd;"><i class="bi bi-journal-text"></i></span>
                        Log Aktivitas
                    </a>
                    <a href="{{ route('admin.documents.index') }}" class="quick-action">
                        <span class="qa-icon" style="background:#f3e5f5;color:#6a1b9a;"><i class="bi bi-file-earmark-text"></i></span>
                        Dokumen K3
                    </a>
                    <a href="{{ route('admin.incident.index') }}" class="quick-action">
                        <span class="qa-icon" style="background:#fdecea;color:#B42323;"><i class="bi bi-exclamation-circle"></i></span>
                        Daftar Insiden
                    </a>
                </div>
            </div>
        </div>
    </div>

{{-- ────────────────────────────────────────────────────────
     VIEWER: Minimal read-only, informasi ringkas
──────────────────────────────────────────────────────── --}}
@elseif($role === 'viewer')

    <div class="alert alert-secondary py-2 px-3 small d-flex align-items-center gap-2 mb-4">
        <i class="bi bi-eye fs-5"></i>
        <span>Anda masuk sebagai <strong>Viewer</strong>. Akses terbatas pada tampilan ringkasan dan dokumen yang disetujui.</span>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-lg-7">
            <div class="card-panel p-4 h-100">
                <div class="section-header">
                    <h6>Ringkasan Keselamatan K3</h6>
                </div>
                {{-- Simplified stats for viewer --}}
                <div class="row g-3 text-center">
                    <div class="col-4">
                        <div class="border rounded p-3">
                            <div class="h2 fw-bold text-success mb-0">{{ $safeDays ?? '—' }}</div>
                            <div class="small text-muted">Hari Aman</div>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="border rounded p-3">
                            <div class="h2 fw-bold {{ $openIncidents > 0 ? 'text-danger' : 'text-success' }} mb-0">{{ $openIncidents }}</div>
                            <div class="small text-muted">Insiden Aktif</div>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="border rounded p-3">
                            <div class="h2 fw-bold {{ $openCapa > 0 ? 'text-warning' : 'text-success' }} mb-0">{{ $openCapa }}</div>
                            <div class="small text-muted">CAPA Terbuka</div>
                        </div>
                    </div>
                </div>
                <hr>
                <canvas id="statusChart" height="120"></canvas>
            </div>
        </div>
        <div class="col-lg-5">
            <div class="card-panel p-4 h-100">
                <h6 class="fw-bold mb-3"><i class="bi bi-file-earmark-text text-primary me-2"></i>Dokumen K3 Tersedia</h6>
                @if($docStats)
                <div class="row g-2 text-center mb-3">
                    <div class="col-6"><div class="border rounded p-2"><div class="h4 fw-bold text-success mb-0">{{ $docStats['approved'] }}</div><div class="small text-muted">Disetujui</div></div></div>
                    <div class="col-6"><div class="border rounded p-2"><div class="h4 fw-bold text-secondary mb-0">{{ $docStats['total'] }}</div><div class="small text-muted">Total</div></div></div>
                </div>
                @endif
                <a href="{{ route('admin.documents.index') }}" class="quick-action">
                    <span class="qa-icon" style="background:#e3f2fd;color:#0277bd;"><i class="bi bi-folder2-open"></i></span>
                    Buka Dokumen K3
                </a>
                <div class="mt-3 text-muted small">
                    <i class="bi bi-info-circle me-1"></i>
                    Anda hanya dapat melihat dokumen yang telah disetujui.
                </div>
            </div>
        </div>
    </div>

@endif

{{-- ══════════════════════════════════════════════════════════
     FOOTER NOTE untuk viewer/auditor: no edit buttons reminder
══════════════════════════════════════════════════════════ --}}
@if(in_array($role, ['viewer', 'auditor']))
<div class="text-center text-muted small mt-2 mb-3">
    <i class="bi bi-lock me-1"></i>
    Tampilan ini bersifat read-only. Tombol tambah/edit/hapus tidak aktif untuk role Anda.
</div>
@endif

@endsection

{{-- ══════════════════════════════════════════════════════════
     SUB-VIEWS (inline includes)
══════════════════════════════════════════════════════════ --}}

{{-- NOTE: Blade tidak support @section di dalam @include biasa untuk sub-views,
     jadi kita gunakan file partials terpisah yang dipanggil via @include --}}

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
Chart.defaults.font.family = "'Inter', sans-serif";
Chart.defaults.color = '#5B6577';

@if(!in_array($role, ['employee']))

// ── Tren Insiden (hanya tampil jika canvas ada)
const trendEl = document.getElementById('trendChart');
if (trendEl) {
    new Chart(trendEl, {
        type: 'line',
        data: {
            labels: @json($trend['labels']),
            datasets: [
                {
                    label: 'Tahun Ini',
                    data: @json($trend['data']),
                    borderColor: '#009EE3',
                    backgroundColor: 'rgba(0,158,227,.12)',
                    fill: true, tension: .35, pointRadius: 4,
                    pointBackgroundColor: '#14489A',
                },
                {
                    label: 'Tahun Lalu',
                    data: @json($trend['data_prev']),
                    borderColor: '#ccc',
                    backgroundColor: 'transparent',
                    borderDash: [5, 4], tension: .35, pointRadius: 3,
                    pointBackgroundColor: '#aaa',
                }
            ]
        },
        options: {
            responsive: true,
            interaction: { mode: 'index', intersect: false },
            plugins: { legend: { position: 'top' } },
            scales: { y: { beginAtZero: true, ticks: { precision: 0 } } }
        }
    });
}

// ── Status Insiden (Doughnut)
const statusEl = document.getElementById('statusChart');
if (statusEl) {
    new Chart(statusEl, {
        type: 'doughnut',
        data: {
            labels: @json($statusChart['labels']),
            datasets: [{
                data: @json($statusChart['data']),
                backgroundColor: ['#B42323', '#F5A623', '#1c7a43'],
                borderWidth: 2, borderColor: '#fff',
            }]
        },
        options: {
            responsive: true, cutout: '65%',
            plugins: { legend: { display: false } }
        }
    });
}

// ── Insiden per Tipe (Horizontal Bar)
const typeEl = document.getElementById('typeChart');
if (typeEl) {
    new Chart(typeEl, {
        type: 'bar',
        data: {
            labels: @json($typeChart['labels']),
            datasets: [{
                label: 'Jumlah',
                data: @json($typeChart['data']),
                backgroundColor: ['#B42323','#F5A623','#14489A','#e85d04','#7048b3'],
                borderRadius: 6, maxBarThickness: 36,
            }]
        },
        options: {
            indexAxis: 'y', responsive: true,
            plugins: { legend: { display: false } },
            scales: { x: { beginAtZero: true, ticks: { precision: 0 } } }
        }
    });
}

// ── Bahaya per Kategori
const hazardEl = document.getElementById('hazardChart');
if (hazardEl) {
    new Chart(hazardEl, {
        type: 'bar',
        data: {
            labels: @json($hazardChart['labels']),
            datasets: [{
                label: 'Jumlah Bahaya',
                data: @json($hazardChart['data']),
                backgroundColor: '#14489A',
                borderRadius: 6, maxBarThickness: 48,
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: { y: { beginAtZero: true, ticks: { precision: 0 } } }
        }
    });
}

@endif
</script>
@endpush