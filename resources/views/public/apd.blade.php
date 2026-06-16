@extends('layouts.public')
@section('title', 'APD')

@section('content')
@include('public._pagehead', [
    'eyebrow' => 'Alat Pelindung Diri',
    'title' => 'APD — Alat Pelindung Diri',
    'subtitle' => 'Standar perlengkapan keselamatan wajib bagi pekerja di lingkungan PT PLN (Persero).',
])

<section class="section">
    <div class="container">
        @if ($apds->isEmpty())
            <div class="text-center text-muted py-5"><i class="bi bi-inbox fs-1"></i><p class="mt-2">Belum ada data APD.</p></div>
        @else
        <div class="row g-4">
            @foreach ($apds as $apd)
                <div class="col-md-6 col-lg-3">
                    <div class="card-k3 h-100 overflow-hidden">
                        <div class="ratio ratio-4x3 bg-light d-flex align-items-center justify-content-center">
                            @if ($apd->image_url)
                                <img src="{{ $apd->image_url }}" alt="{{ $apd->name }}" class="w-100 h-100" style="object-fit: cover;">
                            @else
                                <div class="d-flex align-items-center justify-content-center text-pln"><i class="bi bi-shield-shaded" style="font-size:2.5rem;"></i></div>
                            @endif
                        </div>
                        <div class="p-3">
                            <h6 class="fw-bold mb-1">{{ $apd->name }}</h6>
                            <p class="text-muted small mb-2">{{ $apd->function }}</p>
                            @if ($apd->usage_area)<div class="small"><i class="bi bi-geo-alt text-pln me-1"></i>{{ $apd->usage_area }}</div>@endif
                            @if ($apd->standard)<span class="badge bg-light text-pln border mt-2">{{ $apd->standard }}</span>@endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        @endif
    </div>
</section>
@endsection
