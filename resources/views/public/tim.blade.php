@extends('layouts.public')
@section('title', 'Struktur Tim K3')

@section('content')
@include('public._pagehead', [
    'eyebrow' => 'Organisasi P2K3',
    'title' => 'Struktur Tim K3',
    'subtitle' => 'Panitia Pembina Keselamatan dan Kesehatan Kerja (P2K3) PT PLN (Persero).',
])

<section class="section">
    <div class="container">
        @if ($members->isEmpty())
            <div class="text-center text-muted py-5"><i class="bi bi-inbox fs-1"></i><p class="mt-2">Belum ada data tim.</p></div>
        @else
        <div class="row g-4">
            @foreach ($members as $m)
                <div class="col-md-6 col-lg-4">
                    <div class="card-k3 p-4 h-100">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <div class="card-icon"><i class="bi bi-person-badge"></i></div>
                            @php $levelLabel = \App\Models\TeamMember::LEVELS[$m->level] ?? ucfirst(str_replace('_', ' ', $m->level)); @endphp
                            <span class="badge bg-light text-pln border">{{ $levelLabel }}</span>
                        </div>
                        <h6 class="fw-bold mb-1">{{ $m->position }}</h6>
                        @if ($m->name)<div class="small text-pln mb-1">{{ $m->name }}</div>@endif
                        <p class="text-muted small mb-0">{{ $m->responsibility }}</p>
                    </div>
                </div>
            @endforeach
        </div>
        @endif
    </div>
</section>
@endsection
