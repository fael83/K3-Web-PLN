@extends('layouts.public')
@section('title', 'SOP Keselamatan Kerja')

@section('content')
@include('public._pagehead', [
    'eyebrow' => 'Standard Operating Procedure',
    'title' => 'SOP Keselamatan Kerja',
    'subtitle' => 'Prosedur kerja aman pada setiap tahap pekerjaan ketenagalistrikan.',
])

<section class="section">
    <div class="container">
        @foreach ($sectors as $key => $label)
            @php $list = $steps[$key] ?? collect(); @endphp
            @if ($list->count())
                <div class="mb-5">
                    <h4 class="fw-bold mb-4"><span class="bg-pln-yellow rounded-circle d-inline-block me-2" style="width:10px;height:10px;"></span>{{ $label }}</h4>
                    <div class="row g-3">
                        @foreach ($list as $step)
                            <div class="col-md-6">
                                <div class="card-k3 p-3 d-flex flex-row align-items-start h-100">
                                    <div class="step-num me-3">{{ $step->step_order }}</div>
                                    <div>
                                        <h6 class="fw-bold mb-1">{{ $step->title }}</h6>
                                        <p class="text-muted small mb-0">{{ $step->description }}</p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        @endforeach
    </div>
</section>
@endsection
