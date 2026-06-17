@extends('layouts.public')
@section('title', 'Prosedur Evakuasi')

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
    'eyebrow' => 'Emergency Response',
    'title' => 'Prosedur Evakuasi Darurat',
    'subtitle' => 'Tujuh langkah tanggap darurat saat terjadi keadaan berbahaya di area kerja.',
])

<div style="clear: both; display: block; width: auto; position: relative; padding: 3rem 0; background-color: #f8f9fa;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-9">
                @foreach ($steps as $i => $s)
                    <div class="card-k3 p-4 mb-3 d-flex flex-row align-items-center" style="position: relative; display: flex; height: auto;">
                        <div class="step-num me-3">{{ $i + 1 }}</div>
                        <div class="flex-grow-1">
                            <h6 class="fw-bold mb-1"><i class="bi {{ $s['icon'] }} text-pln me-2"></i>{{ $s['title'] }}</h6>
                            <p class="text-muted small mb-0">{{ $s['desc'] }}</p>
                        </div>
                    </div>
                @endforeach
                <div class="alert alert-warning d-flex align-items-center mt-4 border-0" style="background:#fff6df; position: relative; display: flex;">
                    <i class="bi bi-exclamation-triangle-fill fs-4 me-3 text-warning"></i>
                    <div class="small mb-0">Tetap tenang, jangan panik, jangan gunakan lift, dan ikuti arahan petugas tanggap darurat menuju titik kumpul.</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection