@extends('layouts.public')
@section('title', 'Program Kesehatan Kerja')

@section('content')
@include('public._pagehead', [
    'eyebrow' => 'Occupational Health',
    'title' => 'Program Kesehatan Kerja',
    'subtitle' => 'Upaya menjaga kesehatan fisik dan mental seluruh pekerja PLN.',
])

<section class="section">
    <div class="container">
        @if ($programs->isEmpty())
            <div class="text-center text-muted py-5"><i class="bi bi-inbox fs-1"></i><p class="mt-2">Belum ada data program.</p></div>
        @else
        <div class="row g-4">
            @foreach ($programs as $p)
                <div class="col-md-6 col-lg-4">
                    <div class="card-k3 p-4 h-100">
                        <div class="card-icon mb-3"><i class="bi bi-heart-pulse"></i></div>
                        <h6 class="fw-bold mb-1">{{ $p->program_name }}</h6>
                        <p class="text-muted small mb-0">{{ $p->description }}</p>
                    </div>
                </div>
            @endforeach
        </div>
        @endif
    </div>
</section>
@endsection
