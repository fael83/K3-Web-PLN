<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') — Admin K3 PLN</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@600;700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <link href="{{ asset('assets/app.css') }}" rel="stylesheet">
</head>
<body class="admin-body">

<aside class="sidebar" id="sidebar">
    <div class="brand d-flex align-items-center gap-2">
        <span class="brand-mark"><i class="bi bi-lightning-charge-fill"></i></span>
        <div>
            <div class="text-white fw-bold display-font" style="font-size:1.05rem;">K3 PLN Admin</div>
            <div class="text-white-50" style="font-size:.72rem;">Sistem Informasi K3</div>
        </div>
    </div>
    <div class="flex-grow-1 overflow-auto py-2">
        @php
            $items = [
                ['admin.dashboard', 'bi-speedometer2', 'Dashboard', 'admin/dashboard'],
            ];
        @endphp
        <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <i class="bi bi-speedometer2"></i> Dashboard
        </a>

        <div class="nav-section">Manajemen Data</div>
        <a href="{{ route('admin.apd.index') }}" class="nav-link {{ request()->routeIs('admin.apd.*') ? 'active' : '' }}"><i class="bi bi-shield-shaded"></i> APD</a>
        <a href="{{ route('admin.sop.index') }}" class="nav-link {{ request()->routeIs('admin.sop.*') ? 'active' : '' }}"><i class="bi bi-list-check"></i> SOP</a>
        <a href="{{ route('admin.hazard.index') }}" class="nav-link {{ request()->routeIs('admin.hazard.*') ? 'active' : '' }}"><i class="bi bi-exclamation-triangle"></i> Bahaya</a>
        <a href="{{ route('admin.incident.index') }}" class="nav-link {{ request()->routeIs('admin.incident.*') ? 'active' : '' }}"><i class="bi bi-clipboard-data"></i> Insiden</a>
        <a href="{{ route('admin.team.index') }}" class="nav-link {{ request()->routeIs('admin.team.*') ? 'active' : '' }}"><i class="bi bi-diagram-2"></i> Tim K3</a>
        <a href="{{ route('admin.health.index') }}" class="nav-link {{ request()->routeIs('admin.health.*') ? 'active' : '' }}"><i class="bi bi-heart-pulse"></i> Program Kesehatan</a>

        <div class="nav-section">Sistem</div>
        <a href="{{ route('admin.audit.index') }}" class="nav-link {{ request()->routeIs('admin.audit.*') ? 'active' : '' }}"><i class="bi bi-clock-history"></i> Audit Log</a>
        <a href="{{ route('public.home') }}" target="_blank" class="nav-link"><i class="bi bi-globe"></i> Lihat Situs Publik</a>
    </div>
    <div class="p-3 border-top border-secondary">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="btn btn-outline-light btn-sm w-100"><i class="bi bi-box-arrow-right me-1"></i> Keluar</button>
        </form>
    </div>
</aside>

<div class="admin-main">
    <header class="topbar d-flex align-items-center px-3 sticky-top">
        <button class="btn btn-light d-lg-none me-2" onclick="document.getElementById('sidebar').classList.toggle('show')">
            <i class="bi bi-list"></i>
        </button>
        <h6 class="mb-0 fw-bold">@yield('title', 'Dashboard')</h6>
        <div class="ms-auto d-flex align-items-center gap-2">
            <div class="text-end d-none d-sm-block">
                <div class="small fw-semibold">{{ auth()->user()->name ?? 'Admin' }}</div>
                <div class="text-muted" style="font-size:.72rem;">{{ auth()->user()->email ?? '' }}</div>
            </div>
            <span class="card-icon" style="width:40px;height:40px;font-size:1.1rem;"><i class="bi bi-person"></i></span>
        </div>
    </header>

    <main class="content">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show d-flex align-items-center" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i><div>{{ session('success') }}</div>
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @yield('content')
    </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>
