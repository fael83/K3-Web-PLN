@extends('layouts.public')
@section('title', 'Denah Lokasi')

@section('content')
<style>
    /* Menargetkan teks eyebrow di dalam komponen pagehead */
    .display-font + p + p, 
    .text-uppercase,
    [class*="eyebrow"] { 
        color: #ffc107 !important; 
        opacity: 1 !important;
    }

    /* Menargetkan teks subtitle agar berwarna putih terang dan jelas */
    ._pagehead p, 
    .lead, 
    header p,
    .text-muted-p { 
        color: #bec4cc !important; 
        opacity: 0.9 !important; 
    }
</style>

@include('public._pagehead', [
    'eyebrow' => 'Layout & Jalur Evakuasi',
    'title' => 'Denah Lokasi',
    'subtitle' => 'Tata letak fasilitas, titik APAR, kotak P3K, jalur evakuasi, dan titik kumpul.',
])

<section class="section">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-9">
                <div class="card-k3 p-3">
                    <div class="ratio ratio-16x9 bg-light rounded overflow-hidden">
                        {{-- Coba denah.png, fallback ke denah.svg, lalu placeholder --}}
                        <picture>
                            <source srcset="{{ asset('assets/denahpln.png') }}" type="image/svg+xml">
                            <img src="{{ asset('assets/denahpln.png') }}"
                                 alt="Denah Lokasi PT PLN"
                                 class="w-100 h-100 rounded"
                                 style="object-fit: contain;"
                                 onerror="this.onerror=null; this.src='{{ asset('assets/denahpln.png') }}';">
                        </picture>
                    </div>
                    <div class="mt-2 small text-muted text-center">
                        <i class="bi bi-info-circle me-1"></i>
                        Denah lokasi PT PLN
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
