@extends('layouts.public')
@section('title', 'Profil PLN')

@section('content')
@include('public._pagehead', [
    'eyebrow' => 'Tentang Perusahaan',
    'title' => 'Profil PT PLN (Persero)',
    'subtitle' => 'Badan Usaha Milik Negara yang menyediakan tenaga listrik bagi kepentingan umum di seluruh Indonesia.',
])

<section class="section">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-8">
                <div class="card-k3 p-4 p-lg-5 mb-4">
                    <h4 class="fw-bold mb-3">Identitas Perusahaan</h4>
                    <dl class="row mb-0">
                        <dt class="col-sm-4 text-muted fw-normal">Nama Perusahaan</dt>
                        <dd class="col-sm-8 fw-semibold">PT PLN (Persero)</dd>
                        <dt class="col-sm-4 text-muted fw-normal">Bidang Usaha</dt>
                        <dd class="col-sm-8">Penyediaan tenaga listrik untuk kepentingan umum</dd>
                        <dt class="col-sm-4 text-muted fw-normal">Tahun Berdiri</dt>
                        <dd class="col-sm-8">27 Oktober 1945</dd>
                        <dt class="col-sm-4 text-muted fw-normal">Kantor Pusat</dt>
                        <dd class="col-sm-8">Jl. Trunojoyo Blok M I-135, Kebayoran Baru, Jakarta Selatan</dd>
                        <dt class="col-sm-4 text-muted fw-normal">Rating</dt>
                        <dd class="col-sm-8">idAAA/Stable &middot; Fortune Global 500</dd>
                    </dl>
                </div>

                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="card-k3 p-4 h-100">
                            <div class="card-icon mb-3"><i class="bi bi-eye"></i></div>
                            <h5 class="fw-bold">Visi</h5>
                            <p class="text-muted small mb-0">Menjadi perusahaan listrik terkemuka se-Asia Tenggara dan pilihan pelanggan untuk solusi energi.</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card-k3 p-4 h-100">
                            <div class="card-icon mb-3"><i class="bi bi-bullseye"></i></div>
                            <h5 class="fw-bold">Misi</h5>
                            <p class="text-muted small mb-0">Menjalankan bisnis kelistrikan yang berorientasi pada kepuasan pelanggan, anggota perusahaan, dan pemegang saham secara berkelanjutan.</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card-k3 p-4 mb-4">
                    <h5 class="fw-bold mb-3">Budaya Perusahaan — AKHLAK</h5>
                    @foreach (['Amanah','Kompeten','Harmonis','Loyal','Adaptif','Kolaboratif'] as $b)
                        <span class="badge bg-light text-pln border me-1 mb-1 px-3 py-2">{{ $b }}</span>
                    @endforeach
                </div>
                <div class="card-k3 p-4 mb-4">
                    <h5 class="fw-bold mb-3">5 Klaster PLN</h5>
                    <ul class="list-unstyled small mb-0">
                        <li class="mb-2"><i class="bi bi-check-circle-fill text-pln me-2"></i>Pembangkitan</li>
                        <li class="mb-2"><i class="bi bi-check-circle-fill text-pln me-2"></i>Transmisi</li>
                        <li class="mb-2"><i class="bi bi-check-circle-fill text-pln me-2"></i>Distribusi & Retail</li>
                        <li class="mb-2"><i class="bi bi-check-circle-fill text-pln me-2"></i>Energi Primer & Niaga</li>
                        <li><i class="bi bi-check-circle-fill text-pln me-2"></i>Beyond kWh / Layanan Baru</li>
                    </ul>
                </div>
                <div class="card-k3 p-4 bg-pln text-white" style="background: var(--pln-blue);">
                    <h6 class="fw-bold mb-3 text-warning">Komitmen K3 & Lingkungan</h6>
                    <ul class="list-unstyled small mb-0">
                        <li class="mb-2"><i class="bi bi-patch-check me-2"></i>Penerapan SMK3</li>
                        <li class="mb-2"><i class="bi bi-patch-check me-2"></i>ISO 45001:2018</li>
                        <li class="mb-2"><i class="bi bi-patch-check me-2"></i>ISO 14001:2015</li>
                        <li class="mb-2"><i class="bi bi-patch-check me-2"></i>ISO 9001:2015</li>
                        <li><i class="bi bi-patch-check me-2"></i>Target Net Zero Emission 2060</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
