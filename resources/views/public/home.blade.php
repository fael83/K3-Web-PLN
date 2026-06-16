@extends('layouts.public')
@section('title', 'Beranda')

@section('content')
<section class="hero">
    <div class="container py-5">
        <div class="row align-items-center g-5 py-lg-4">
            <div class="col-lg-7">
                <span class="eyebrow"><i class="bi bi-shield-check"></i> Keselamatan & Kesehatan Kerja</span>
                <h1 class="mt-3 mb-3">Sistem Informasi K3<br>PT PLN (Persero)</h1>
                <p class="lead fs-5 mb-4">Mewujudkan <strong class="text-warning">Zero Harm, Zero Loss</strong> di seluruh lini operasional ketenagalistrikan nasional.</p>
                <div class="d-flex flex-wrap gap-2">
                    <a href="{{ route('public.apd') }}" class="btn btn-warning fw-semibold px-4 py-2">Jelajahi Konten K3</a>
                    <a href="{{ route('public.profil') }}" class="btn btn-outline-light px-4 py-2">Tentang PLN</a>
                </div>
            </div>
            <div class="col-lg-5">
                <div class="row g-3">
                    @foreach ($stats as $s)
                        <div class="col-6">
                            <div class="stat-card p-3 h-100">
                                <i class="bi {{ $s['icon'] }} stat-icon"></i>
                                <div class="stat-value mt-2">{{ $s['value'] }}</div>
                                <div class="small text-white-50">{{ $s['unit'] }} &middot; {{ $s['label'] }}</div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="text-center mb-5">
            <div class="section-eyebrow">Pusat Informasi</div>
            <h2 class="section-title mt-2">Konten K3 Terpadu</h2>
            <p class="text-muted mx-auto" style="max-width: 52ch;">Repositori informasi keselamatan kerja yang menjadi acuan budaya K3 di lingkungan PT PLN (Persero).</p>
        </div>
        <div class="row g-4">
            @foreach ($menus as $m)
                <div class="col-md-6 col-lg-4">
                    <a href="{{ route($m['route']) }}" class="text-decoration-none text-reset">
                        <div class="card-k3 p-4">
                            <div class="card-icon mb-3"><i class="bi {{ $m['icon'] }}"></i></div>
                            <h5 class="fw-bold mb-2">{{ $m['title'] }}</h5>
                            <p class="text-muted small mb-3">{{ $m['desc'] }}</p>
                            <span class="text-pln fw-semibold small">Lihat detail <i class="bi bi-arrow-right ms-1"></i></span>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    </div>
</section>

<section class="section section-alt">
    <div class="container">
        <div class="row align-items-center g-4">
            <div class="col-lg-6">
                <div class="section-eyebrow">Komitmen Korporat</div>
                <h2 class="section-title mt-2 mb-3">Budaya Keselamatan Berstandar Internasional</h2>
                <p class="text-muted">PT PLN (Persero) menerapkan Sistem Manajemen K3 (SMK3) dan tersertifikasi standar internasional untuk memastikan setiap pekerja pulang dengan selamat.</p>
                <div class="d-flex flex-wrap gap-2 mt-3">
                    <span class="badge bg-white text-pln border px-3 py-2">ISO 45001:2018</span>
                    <span class="badge bg-white text-pln border px-3 py-2">ISO 14001:2015</span>
                    <span class="badge bg-white text-pln border px-3 py-2">ISO 9001:2015</span>
                    <span class="badge bg-white text-pln border px-3 py-2">Menuju NZE 2060</span>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="row g-3">
                    <div class="col-6"><div class="card-k3 p-4 text-center"><div class="display-6 fw-bold text-pln">idAAA</div><div class="small text-muted">Rating /Stable</div></div></div>
                    <div class="col-6"><div class="card-k3 p-4 text-center"><div class="display-6 fw-bold text-pln">500</div><div class="small text-muted">Fortune Global 500</div></div></div>
                    <div class="col-12"><div class="card-k3 p-4 text-center"><div class="h4 fw-bold text-pln mb-0">5 Klaster Operasional</div><div class="small text-muted">Pembangkitan, Transmisi, Distribusi, dan unit pendukung</div></div></div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
