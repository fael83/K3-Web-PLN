@php($actionUrl = $actionUrl ?? null)
<div class="d-flex flex-wrap align-items-center gap-2 mb-4">
    <div>
        <h4 class="mb-0 fw-bold display-font">{{ $heading }}</h4>
        @isset($subheading)
            <div class="text-muted small">{{ $subheading }}</div>
        @endisset
    </div>
    @isset($actionLabel)
        <a href="{{ $actionUrl }}" class="btn btn-pln ms-auto">
            <i class="bi bi-plus-lg me-1"></i> {{ $actionLabel }}
        </a>
    @endisset
</div>
