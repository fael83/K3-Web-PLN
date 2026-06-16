{{-- resources/views/layouts/public.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Beranda') — Sistem Informasi K3 PT PLN (Persero)</title>
    <meta name="description" content="Sistem Informasi Keselamatan dan Kesehatan Kerja (K3) PT PLN (Persero).">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@500;600;700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <link href="{{ asset('assets/app.css') }}" rel="stylesheet">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark navbar-pln sticky-top">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center gap-2" href="{{ route('public.home') }}">
            <span class="brand-mark"><i class="bi bi-lightning-charge-fill"></i></span>
            <span>K3 <span class="text-warning">PLN</span></span>
        </a>
        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="mainNav">
            <ul class="navbar-nav ms-auto align-items-lg-center">
                @php
                    $nav = [
                        'public.home'      => 'Home',
                        'public.profil'    => 'Profil PLN',
                        'public.bahaya'    => 'Bahaya',
                        'public.risiko'    => 'Risiko K3',
                        'public.apd'       => 'APD',
                        'public.sop'       => 'SOP',
                        'public.evakuasi'  => 'Evakuasi',
                        'public.kesehatan' => 'Kesehatan',
                        'public.tim'       => 'Tim K3',
                        'public.denah'     => 'Denah',
                        'public.kesimpulan'=> 'Kesimpulan',
                    ];
                @endphp
                @foreach ($nav as $route => $label)
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs($route) ? 'active' : '' }}"
                           href="{{ route($route) }}">{{ $label }}</a>
                    </li>
                @endforeach
                <li class="nav-item ms-lg-2">
                    <a class="btn btn-warning btn-sm fw-semibold px-3" href="{{ route('login') }}">
                        <i class="bi bi-box-arrow-in-right me-1"></i>Login Admin
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>

@yield('content')

<footer class="footer-pln pt-5 pb-4 mt-0">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-5">
                <div class="d-flex align-items-center gap-2 mb-3">
                    <span class="brand-mark"><i class="bi bi-lightning-charge-fill"></i></span>
                    <span class="h5 mb-0 text-white display-font fw-bold">K3 PT PLN (Persero)</span>
                </div>
                <p class="small mb-0">Sistem Informasi Keselamatan dan Kesehatan Kerja untuk mewujudkan
                    <span class="text-warning fw-semibold">Zero Harm, Zero Loss</span> di seluruh lini operasional.</p>
            </div>
            <div class="col-lg-4">
                <h6 class="text-white mb-3">Navigasi</h6>
                <div class="row small">
                    <div class="col-6">
                        <a class="d-block mb-2" href="{{ route('public.profil') }}">Profil PLN</a>
                        <a class="d-block mb-2" href="{{ route('public.apd') }}">APD</a>
                        <a class="d-block mb-2" href="{{ route('public.sop') }}">SOP Keselamatan</a>
                    </div>
                    <div class="col-6">
                        <a class="d-block mb-2" href="{{ route('public.evakuasi') }}">Prosedur Evakuasi</a>
                        <a class="d-block mb-2" href="{{ route('public.tim') }}">Struktur Tim K3</a>
                        <a class="d-block mb-2" href="{{ route('public.denah') }}">Denah Lokasi</a>
                    </div>
                </div>
            </div>
            <div class="col-lg-3">
                <h6 class="text-white mb-3">Kantor Pusat</h6>
                <p class="small mb-0">Jl. Trunojoyo Blok M I-135<br>Kebayoran Baru, Jakarta Selatan<br>Berdiri 27 Oktober 1945</p>
            </div>
        </div>
        <hr class="border-secondary my-4">
        <p class="small mb-0 text-center">&copy; {{ date('Y') }} PT PLN (Persero) — Sistem Informasi K3.</p>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>