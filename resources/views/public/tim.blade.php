@extends('layouts.public')
@section('title', 'Struktur Tim K3')

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
    'eyebrow' => 'Organisasi P2K3',
    'title'    => 'Struktur Tim K3',
    'subtitle' => 'Susunan organisasi Panitia Pembina Keselamatan dan Kesehatan Kerja (P2K3) PT PLN (Persero).',
])

<section class="section">
    <div class="container">

        @if($team->isEmpty())
            <div class="text-center text-muted py-5">
                <i class="bi bi-people fs-1 d-block mb-3"></i>
                <p>Belum ada data anggota tim K3.</p>
            </div>
        @else
            <div class="row g-4">
                @foreach($team as $member)
                    <div class="col-md-6 col-lg-4">
                        <div class="card-k3 h-100 p-4 d-flex flex-column align-items-center text-center">

                            {{-- Foto --}}
                            <div class="mb-3">
                                @if(!empty($member->foto))
                                    <img src="{{ asset('storage/' . $member->foto) }}"
                                         alt="{{ $member->nama }}"
                                         class="rounded-circle"
                                         style="width:90px; height:90px; object-fit:cover;">
                                @else
                                    <div class="rounded-circle bg-light d-flex align-items-center justify-content-center text-pln"
                                         style="width:90px; height:90px;">
                                        <i class="bi bi-person-fill" style="font-size:2.5rem;"></i>
                                    </div>
                                @endif
                            </div>

                            {{-- Nama --}}
                            <h5 class="fw-bold mb-1">{{ $member->nama ?? '-' }}</h5>

                            {{-- Jabatan --}}
                            <span class="badge bg-warning text-dark mb-2">{{ $member->jabatan ?? '-' }}</span>

                            {{-- Tanggung jawab --}}
                            @if(!empty($member->responsibility))
                                <p class="text-muted small mb-0">{{ $member->responsibility }}</p>
                            @endif

                        </div>
                    </div>
                @endforeach
            </div>
        @endif

    </div>
</section>
@endsection