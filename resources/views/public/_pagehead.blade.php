{{-- @include('public._pagehead', ['eyebrow' => '...', 'title' => '...', 'subtitle' => '...']) --}}
<section class="hero">
    <div class="container py-5">
        <div class="py-lg-2" style="max-width: 60ch;">
            <span class="eyebrow"><i class="bi bi-shield-check"></i> {{ $eyebrow ?? 'Informasi K3' }}</span>
            <h1 class="mt-3 mb-2" style="font-size: clamp(1.8rem, 4vw, 2.8rem);">{{ $title }}</h1>
            @isset($subtitle)
                <p class="lead mb-0">{{ $subtitle }}</p>
            @endisset
        </div>
    </div>
</section>
