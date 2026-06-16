@extends('layouts.public')
@section('title', 'Identifikasi Bahaya')

@section('content')
@include('public._pagehead', [
    'eyebrow' => 'Hazard Identification',
    'title' => 'Identifikasi Bahaya',
    'subtitle' => 'Lima kategori bahaya utama yang menjadi perhatian dalam pekerjaan ketenagalistrikan PLN.',
])

<section class="section">
    <div class="container">
        <div class="row g-4">
            @foreach ($categories as $key => $c)
                <div class="col-md-6 col-lg-4">
                    <div class="card-k3 p-4 h-100">
                        <div class="card-icon warn mb-3"><i class="bi {{ $c['icon'] }}"></i></div>
                        <h5 class="fw-bold mb-2">{{ $c['title'] }}</h5>
                        <p class="text-muted small">{{ $c['desc'] }}</p>

                        @if (isset($grouped[$key]) && $grouped[$key]->count())
                            <hr class="my-3">
                            <div class="small fw-semibold text-muted mb-2">Contoh teridentifikasi:</div>
                            @foreach ($grouped[$key] as $h)
                                <div class="d-flex justify-content-between align-items-center small mb-1">
                                    <span><i class="bi bi-dot"></i>{{ $h->name }}</span>
                                    <span class="lvl lvl-{{ $h->severity }}">{{ ucfirst($h->severity) }}</span>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endsection
