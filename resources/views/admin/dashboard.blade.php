@extends('layouts.admin')
@section('title', 'Dashboard')

@section('content')

{{-- ══════════════════════════════════════════════
     KPI CARDS
══════════════════════════════════════════════ --}}
<div class="row g-3 mb-4">

    {{-- Safe Days --}}
    <div class="col-6 col-md-4 col-xl-2">
        <div class="stat-tile p-3 h-100 text-center">
            <div class="icn mb-2 mx-auto" style="background:rgba(28,122,67,.1);color:#1c7a43;width:44px;height:44px;border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:1.3rem;">
                <i class="bi bi-shield-check"></i>
            </div>
            <div class="h3 fw-bold mb-0 text-success">
                {{ $safeDays !== null ? $safeDays : '—' }}
            </div>
            <div class="small text-muted">Hari Tanpa Insiden</div>
        </div>
    </div>

    {{-- Open Incidents --}}
    <div class="col-6 col-md-4 col-xl-2">
        <div class="stat-tile p-3 h-100 text-center">
            <div class="icn mb-2 mx-auto" style="background:rgba(180,35,35,.1);color:#B42323;width:44px;height:44px;border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:1.3rem;">
                <i class="bi bi-exclamation-circle"></i>
            </div>
            <div class="h3 fw-bold mb-0 {{ $openIncidents > 0 ? 'text-danger' : 'text-success' }}">
                {{ $openIncidents }}
            </div>
            <div class="small text-muted">Insiden Terbuka</div>
        </div>
    </div>

    {{-- Open CAPA --}}
    <div class="col-6 col-md-4 col-xl-2">
        <div class="stat-tile p-3 h-100 text-center">
            <div class="icn mb-2 mx-auto" style="background:rgba(245,166,35,.14);color:#F5A623;width:44px;height:44px;border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:1.3rem;">
                <i class="bi bi-tools"></i>
            </div>
            <div class="h3 fw-bold mb-0 {{ $openCapa > 0 ? 'text-warning' : 'text-success' }}">
                {{ $openCapa }}
            </div>
            <div class="small text-muted">CAPA Belum Selesai</div>
        </div>
    </div>

    {{-- Open Audit Findings --}}
    <div class="col-6 col-md-4 col-xl-2">
        <div class="stat-tile p-3 h-100 text-center">
            <div class="icn mb-2 mx-auto" style="background:rgba(112,72,179,.1);color:#7048b3;width:44px;height:44px;border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:1.3rem;">
                <i class="bi bi-clipboard-check"></i>
            </div>
            <div class="h3 fw-bold mb-0 {{ $openFindings > 0 ? 'text-warning' : 'text-success' }}">
                {{ $openFindings }}
            </div>
            <div class="small text-muted">Temuan Audit Terbuka</div>
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

{{-- ══════════════════════════════════════════════
     ROW 2: Tren Insiden + Status Insiden
══════════════════════════════════════════════ --}}
<div class="row g-3 mb-4">
    <div class="col-lg-8">
        <div class="card-panel p-4 h-100">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="fw-bold mb-0">Tren Insiden — Tahun Ini vs Tahun Lalu</h6>
                <span class="badge bg-primary-subtle text-primary-emphasis">12 Bulan</span>
            </div>
            <canvas id="trendChart" height="90"></canvas>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card-panel p-4 h-100">
            <h6 class="fw-bold mb-3">Status Insiden</h6>
            <canvas id="statusChart" height="200"></canvas>
            <div class="mt-3 d-flex flex-column gap-1">
                <div class="d-flex justify-content-between small">
                    <span class="text-danger"><i class="bi bi-circle-fill me-1" style="font-size:.5rem;"></i>Open</span>
                    <strong>{{ collect($statusChart['data'])[0] ?? 0 }}</strong>
                </div>
                <div class="d-flex justify-content-between small">
                    <span class="text-warning"><i class="bi bi-circle-fill me-1" style="font-size:.5rem;"></i>Investigasi</span>
                    <strong>{{ collect($statusChart['data'])[1] ?? 0 }}</strong>
                </div>
                <div class="d-flex justify-content-between small">
                    <span class="text-success"><i class="bi bi-circle-fill me-1" style="font-size:.5rem;"></i>Selesai</span>
                    <strong>{{ collect($statusChart['data'])[2] ?? 0 }}</strong>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ══════════════════════════════════════════════
     ROW 3: Insiden per Tipe + Bahaya per Kategori
══════════════════════════════════════════════ --}}
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

{{-- ══════════════════════════════════════════════
     ROW 4: Audit Summary + Quick Actions
══════════════════════════════════════════════ --}}
<div class="row g-3 mb-4">
    <div class="col-lg-4">
        <div class="card-panel p-4 h-100">
            <h6 class="fw-bold mb-3">
                <i class="bi bi-clipboard-check text-primary me-2"></i>Ringkasan Audit
            </h6>
            <div class="row g-2 text-center">
                <div class="col-6">
                    <div class="border rounded p-2">
                        <div class="h4 fw-bold text-success mb-0">{{ $auditSummary['completed'] }}</div>
                        <div class="small text-muted">Selesai</div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="border rounded p-2">
                        <div class="h4 fw-bold text-warning mb-0">{{ $auditSummary['in_progress'] }}</div>
                        <div class="small text-muted">Berlangsung</div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="border rounded p-2">
                        <div class="h4 fw-bold text-secondary mb-0">{{ $auditSummary['draft'] }}</div>
                        <div class="small text-muted">Draft</div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="border rounded p-2">
                        <div class="h4 fw-bold mb-0">{{ $auditSummary['total'] }}</div>
                        <div class="small text-muted">Total</div>
                    </div>
                </div>
            </div>
            <div class="mt-3">
                @if($openFindings > 0)
                <div class="alert alert-warning py-2 px-3 small mb-0">
                    <i class="bi bi-exclamation-triangle me-1"></i>
                    <strong>{{ $openFindings }}</strong> temuan audit belum ditindaklanjuti.
                    <a href="{{ route('admin.audit-checklist.index') }}" class="alert-link">Lihat →</a>
                </div>
                @else
                <div class="alert alert-success py-2 px-3 small mb-0">
                    <i class="bi bi-check-circle me-1"></i>Semua temuan audit sudah ditindaklanjuti.
                </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card-panel p-4 h-100">
            <h6 class="fw-bold mb-3">
                <i class="bi bi-clock-history text-primary me-2"></i>Aktivitas Terbaru
            </h6>
            <div class="d-flex flex-column gap-2">
                @forelse($recentAuditLogs as $log)
                <div class="d-flex align-items-start gap-2">
                    <span class="badge bg-secondary-subtle text-secondary-emphasis mt-1" style="font-size:.65rem;">
                        {{ strtoupper(substr($log->action, 0, 3)) }}
                    </span>
                    <div style="font-size:.8rem;">
                        <div class="fw-semibold">{{ $log->user->name ?? 'Sistem' }}</div>
                        <div class="text-muted">{{ Str::limit($log->description, 45) }}</div>
                        <div class="text-muted" style="font-size:.72rem;">{{ $log->created_at?->diffForHumans() }}</div>
                    </div>
                </div>
                @empty
                <p class="text-muted small">Belum ada aktivitas.</p>
                @endforelse
            </div>
            <a href="{{ route('admin.audit.index') }}" class="btn btn-outline-secondary btn-sm w-100 mt-3">
                Lihat Semua Log
            </a>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card-panel p-4 h-100">
            <h6 class="fw-bold mb-3">
                <i class="bi bi-lightning text-warning me-2"></i>Aksi Cepat
            </h6>
            <div class="d-grid gap-2">
                <a href="{{ route('admin.incident.create') }}" class="btn btn-danger text-start">
                    <i class="bi bi-plus-circle me-2"></i>Catat Insiden
                </a>
                <a href="{{ route('admin.audit-checklist.create') }}" class="btn btn-primary text-start">
                    <i class="bi bi-clipboard-plus me-2"></i>Buat Audit Checklist
                </a>
                <a href="{{ route('admin.hazard.create') }}" class="btn btn-warning text-start text-dark">
                    <i class="bi bi-plus-circle me-2"></i>Tambah Bahaya
                </a>
                <a href="{{ route('admin.audit-evidence.index') }}" class="btn btn-outline-primary text-start">
                    <i class="bi bi-archive me-2"></i>Generate Evidence Package
                </a>
                <a href="{{ route('admin.sop.create') }}" class="btn btn-outline-secondary text-start">
                    <i class="bi bi-plus-circle me-2"></i>Tambah SOP
                </a>
            </div>
        </div>
    </div>
</div>

{{-- ══════════════════════════════════════════════
     ROW 5: Tabel Insiden Terbaru
══════════════════════════════════════════════ --}}
<div class="row g-3">
    <div class="col-12">
        <div class="card-panel p-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="fw-bold mb-0">Insiden Terbaru</h6>
                <a href="{{ route('admin.incident.index') }}" class="small text-pln text-decoration-none">
                    Lihat semua <i class="bi bi-arrow-right ms-1"></i>
                </a>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Judul</th>
                            <th class="d-none d-md-table-cell">Tipe</th>
                            <th class="d-none d-md-table-cell">Lokasi</th>
                            <th>Tanggal</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($recentIncidents as $inc)
                            <tr>
                                <td class="fw-semibold small">{{ $inc->title }}</td>
                                <td class="small text-muted d-none d-md-table-cell">
                                    {{ \App\Models\Incident::TYPES[$inc->incident_type] ?? $inc->incident_type }}
                                </td>
                                <td class="small text-muted d-none d-md-table-cell">{{ $inc->location ?? '—' }}</td>
                                <td class="small text-muted">
                                    {{ optional($inc->incident_date)->format('d M Y') ?? '—' }}
                                </td>
                                <td>
                                    @php $map = ['open'=>'danger','investigasi'=>'warning','selesai'=>'success']; @endphp
                                    <span class="badge bg-{{ $map[$inc->status] ?? 'secondary' }}-subtle text-{{ $map[$inc->status] ?? 'secondary' }}-emphasis">
                                        {{ \App\Models\Incident::STATUSES[$inc->status] ?? $inc->status }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">
                                    <i class="bi bi-inbox d-block display-6 mb-2"></i>
                                    Belum ada insiden tercatat.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>
<script>
Chart.defaults.font.family = "'Inter', sans-serif";
Chart.defaults.color = '#5B6577';

// ── Tren Insiden (Line, YoY) ──────────────────────────────────────────────
new Chart(document.getElementById('trendChart'), {
    type: 'line',
    data: {
        labels: @json($trend['labels']),
        datasets: [
            {
                label: 'Tahun Ini',
                data: @json($trend['data']),
                borderColor: '#009EE3',
                backgroundColor: 'rgba(0,158,227,.12)',
                fill: true,
                tension: .35,
                pointRadius: 4,
                pointBackgroundColor: '#14489A',
            },
            {
                label: 'Tahun Lalu',
                data: @json($trend['data_prev']),
                borderColor: '#ccc',
                backgroundColor: 'transparent',
                borderDash: [5, 4],
                tension: .35,
                pointRadius: 3,
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

// ── Status Insiden (Doughnut) ─────────────────────────────────────────────
new Chart(document.getElementById('statusChart'), {
    type: 'doughnut',
    data: {
        labels: @json($statusChart['labels']),
        datasets: [{
            data: @json($statusChart['data']),
            backgroundColor: ['#B42323', '#F5A623', '#1c7a43'],
            borderWidth: 2,
            borderColor: '#fff',
        }]
    },
    options: {
        responsive: true,
        cutout: '65%',
        plugins: { legend: { display: false } }
    }
});

// ── Insiden per Tipe (Horizontal Bar) ────────────────────────────────────
new Chart(document.getElementById('typeChart'), {
    type: 'bar',
    data: {
        labels: @json($typeChart['labels']),
        datasets: [{
            label: 'Jumlah',
            data: @json($typeChart['data']),
            backgroundColor: ['#B42323','#F5A623','#14489A','#e85d04','#7048b3'],
            borderRadius: 6,
            maxBarThickness: 36,
        }]
    },
    options: {
        indexAxis: 'y',
        responsive: true,
        plugins: { legend: { display: false } },
        scales: { x: { beginAtZero: true, ticks: { precision: 0 } } }
    }
});

// ── Bahaya per Kategori (Bar) ─────────────────────────────────────────────
new Chart(document.getElementById('hazardChart'), {
    type: 'bar',
    data: {
        labels: @json($hazardChart['labels']),
        datasets: [{
            label: 'Jumlah Bahaya',
            data: @json($hazardChart['data']),
            backgroundColor: '#14489A',
            borderRadius: 6,
            maxBarThickness: 48,
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: { y: { beginAtZero: true, ticks: { precision: 0 } } }
    }
});
</script>
@endpush
