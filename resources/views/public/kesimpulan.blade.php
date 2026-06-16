@extends('layouts.public')
@section('title', 'Kesimpulan & Saran')

@section('content')
@include('public._pagehead', [
    'eyebrow' => 'Penutup',
    'title' => 'Kesimpulan & Saran',
    'subtitle' => 'Rangkuman penerapan K3 dan rekomendasi peningkatan keselamatan kerja di PT PLN (Persero).',
])

<section class="section">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-5">
                <div class="card-k3 p-4 p-lg-5 h-100">
                    <div class="card-icon mb-3"><i class="bi bi-journal-check"></i></div>
                    <h4 class="fw-bold mb-3">Kesimpulan</h4>
                    <p class="text-muted">Keselamatan dan Kesehatan Kerja merupakan aspek fundamental dalam operasional ketenagalistrikan PT PLN (Persero). Penerapan SMK3 yang konsisten, identifikasi bahaya yang menyeluruh, serta budaya keselamatan yang kuat menjadi kunci dalam mewujudkan <strong class="text-pln">Zero Harm, Zero Loss</strong>. Sistem informasi K3 ini diharapkan menjadi pusat acuan yang memperkuat kesadaran dan kepatuhan seluruh pekerja terhadap standar keselamatan.</p>
                </div>
            </div>
            <div class="col-lg-7">
                <div class="card-k3 p-4 p-lg-5 h-100">
                    <h4 class="fw-bold mb-4">Saran K3</h4>
                    @foreach ($saran as $i => $s)
                        <div class="d-flex mb-3">
                            <div class="step-num me-3" style="width:34px;height:34px;flex:0 0 34px;font-size:.9rem;">{{ $i + 1 }}</div>
                            <p class="small text-muted mb-0 align-self-center">{{ $s }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
