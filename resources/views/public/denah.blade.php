@extends('layouts.public')
@section('title', 'Denah Lokasi')

@section('content')
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
                            <source srcset="{{ asset('assets/denah.svg') }}" type="image/svg+xml">
                            <img src="{{ asset('assets/denah.png') }}"
                                 alt="Denah Lokasi PT PLN"
                                 class="w-100 h-100 rounded"
                                 style="object-fit: contain;"
                                 onerror="this.onerror=null; this.src='{{ asset('assets/denah.svg') }}';">
                        </picture>
                    </div>
                    <div class="mt-2 small text-muted text-center">
                        <i class="bi bi-info-circle me-1"></i>
                        Ganti <code>public/assets/denah.png</code> dengan denah gedung kantor Anda yang sesungguhnya.
                    </div>
                </div>
            </div>
            <div class="col-lg-3">
                <div class="card-k3 p-4 h-100">
                    <h6 class="fw-bold mb-3">Legenda</h6>
                    @foreach ($legend as $l)
                        <div class="d-flex align-items-center mb-2">
                            <span class="card-icon me-3" style="width:36px;height:36px;font-size:.95rem;flex:0 0 36px;">
                                <i class="bi {{ $l['icon'] }}"></i>
                            </span>
                            <span class="small">{{ $l['label'] }}</span>
                        </div>
                    @endforeach

                    <hr class="my-3">
                    <div class="small">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <span style="width:24px;height:12px;background:#B42323;border-radius:3px;display:inline-block;flex:0 0 24px;"></span>
                            <span class="text-muted">APAR (Alat Pemadam)</span>
                        </div>
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <span style="width:24px;height:12px;background:#1c7a43;border-radius:3px;display:inline-block;flex:0 0 24px;"></span>
                            <span class="text-muted">Kotak P3K</span>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <span style="width:24px;height:4px;background:#FFC20E;display:inline-block;flex:0 0 24px;border-radius:2px;"></span>
                            <span class="text-muted">Jalur Evakuasi</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Info Titik Kumpul --}}
        <div class="alert d-flex align-items-start gap-3 mt-4 border-0" style="background:#fff6df;">
            <i class="bi bi-geo-alt-fill fs-4 text-warning flex-shrink-0 mt-1"></i>
            <div>
                <div class="fw-semibold">Titik Kumpul (Assembly Point)</div>
                <div class="small text-muted">Saat alarm berbunyi, seluruh penghuni gedung wajib berkumpul di titik kumpul yang telah ditentukan di area parkir depan gedung. Jangan kembali ke dalam gedung sebelum mendapat izin dari petugas tanggap darurat.</div>
            </div>
        </div>
    </div>
</section>
@endsection
