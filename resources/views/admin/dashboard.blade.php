@extends('layouts.admin')
@section('title', 'Dashboard')

@section('content')
{{-- Stat tiles --}}
<div class="row g-3 mb-4">
    @php
        $tiles = [
            ['APD', $counts['apd'], 'bi-shield-shaded', '#14489A', 'rgba(20,72,154,.1)', route('admin.apd.index')],
            ['SOP', $counts['sop'], 'bi-list-check', '#009EE3', 'rgba(0,158,227,.12)', route('admin.sop.index')],
            ['Bahaya', $counts['hazard'], 'bi-exclamation-triangle', '#F5A623', 'rgba(245,166,35,.14)', route('admin.hazard.index')],
            ['Insiden', $counts['incident'], 'bi-clipboard-data', '#B42323', 'rgba(180,35,35,.1)', route('admin.incident.index')],
            ['Tim K3', $counts['team'], 'bi-diagram-2', '#1c7a43', 'rgba(28,122,67,.1)', route('admin.team.index')],
            ['Program Kesehatan', $counts['health'], 'bi-heart-pulse', '#7048b3', 'rgba(112,72,179,.1)', route('admin.health.index')],
        ];
    @endphp
    @foreach ($tiles as [$label, $val, $icon, $color, $bg, $url])
        <div class="col-6 col-md-4 col-xl-2">
            <a href="{{ $url }}" class="text-decoration-none text-reset">
                <div class="stat-tile p-3 h-100">
                    <div class="icn mb-2" style="background: {{ $bg }}; color: {{ $color }};"><i class="bi {{ $icon }}"></i></div>
                    <div class="h3 fw-bold mb-0">{{ $val }}</div>
                    <div class="small text-muted">Total {{ $label }}</div>
                </div>
            </a>
        </div>
    @endforeach
</div>

{{-- Charts --}}
<div class="row g-3 mb-4">
    <div class="col-lg-7">
        <div class="card-panel p-4 h-100">
            <h6 class="fw-bold mb-3">Bahaya per Kategori</h6>
            <canvas id="hazardChart" height="120"></canvas>
        </div>
    </div>
    <div class="col-lg-5">
        <div class="card-panel p-4 h-100">
            <h6 class="fw-bold mb-3">Status Insiden</h6>
            <canvas id="statusChart" height="160"></canvas>
        </div>
    </div>
    <div class="col-12">
        <div class="card-panel p-4">
            <h6 class="fw-bold mb-3">Tren Insiden (12 Bulan Terakhir)</h6>
            <canvas id="trendChart" height="80"></canvas>
        </div>
    </div>
</div>

{{-- Recent incidents + quick actions --}}
<div class="row g-3">
    <div class="col-lg-8">
        <div class="card-panel p-4 h-100">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="fw-bold mb-0">Insiden Terbaru</h6>
                <a href="{{ route('admin.incident.index') }}" class="small text-pln text-decoration-none">Lihat semua</a>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead><tr><th>Judul</th><th>Lokasi</th><th>Tanggal</th><th>Status</th></tr></thead>
                    <tbody>
                        @forelse ($recentIncidents as $inc)
                            <tr>
                                <td class="fw-semibold small">{{ $inc->title }}</td>
                                <td class="small text-muted">{{ $inc->location ?? '-' }}</td>
                                <td class="small text-muted">{{ optional($inc->incident_date)->format('d M Y') ?? '-' }}</td>
                                <td>
                                    @php $map = ['open'=>'danger','investigasi'=>'warning','selesai'=>'success']; @endphp
                                    <span class="badge bg-{{ $map[$inc->status] ?? 'secondary' }}-subtle text-{{ $map[$inc->status] ?? 'secondary' }} text-capitalize">{{ \App\Models\Incident::STATUSES[$inc->status] ?? $inc->status }}</span>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="text-center text-muted py-4">Belum ada insiden tercatat.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card-panel p-4 h-100">
            <h6 class="fw-bold mb-3">Aksi Cepat</h6>
            <div class="d-grid gap-2">
                <a href="{{ route('admin.incident.create') }}" class="btn btn-pln text-start"><i class="bi bi-plus-circle me-2"></i>Catat Insiden</a>
                <a href="{{ route('admin.hazard.create') }}" class="btn btn-outline-secondary text-start"><i class="bi bi-plus-circle me-2"></i>Tambah Bahaya</a>
                <a href="{{ route('admin.apd.create') }}" class="btn btn-outline-secondary text-start"><i class="bi bi-plus-circle me-2"></i>Tambah APD</a>
                <a href="{{ route('admin.sop.create') }}" class="btn btn-outline-secondary text-start"><i class="bi bi-plus-circle me-2"></i>Tambah SOP</a>
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
            cutout: '62%',
            plugins: { legend: { position: 'bottom' } }
        }
    });

    new Chart(document.getElementById('trendChart'), {
        type: 'line',
        data: {
            labels: @json($trend['labels']),
            datasets: [{
                label: 'Insiden',
                data: @json($trend['data']),
                borderColor: '#009EE3',
                backgroundColor: 'rgba(0,158,227,.12)',
                fill: true,
                tension: .35,
                pointRadius: 3,
                pointBackgroundColor: '#14489A',
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
