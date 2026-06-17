@extends('layouts.public')
@section('title', 'Risiko K3')

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
    'eyebrow' => 'Risk Assessment',
    'title' => 'Risiko K3 Ketenagalistrikan',
    'subtitle' => 'Sembilan risiko utama yang harus dikendalikan dalam setiap aktivitas operasional.',
])

<section class="section">
    <div class="container">
        <div class="row g-4">
            @foreach ($risks as $i => $r)
                <div class="col-md-6 col-lg-4">
                    <div class="card-k3 p-4 h-100 d-flex">
                        <div class="step-num me-3">{{ $i + 1 }}</div>
                        <div>
                            <h6 class="fw-bold mb-1"><i class="bi {{ $r['icon'] }} text-pln me-1"></i>{{ $r['name'] }}</h6>
                            <p class="text-muted small mb-0">{{ $r['desc'] }}</p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endsection
