{{-- HERO PAGE HEADER (SAFE + REUSABLE COMPONENT) --}}
<section class="hero bg-light border-bottom">
    <div class="container py-5">

        <div class="py-lg-2" style="max-width: 60ch;">

            {{-- EYEBROW --}}
            <span class="eyebrow d-block text-muted mb-2">
                <i class="bi bi-shield-check"></i>
                {{ $eyebrow ?? 'Informasi K3' }}
            </span>

            {{-- TITLE --}}
            <h1 class="mt-2 mb-2 fw-bold" style="font-size: clamp(1.8rem, 4vw, 2.8rem);">
                {{ $title ?? 'Halaman K3 PLN' }}
            </h1>

            {{-- SUBTITLE --}}
            @if(!empty($subtitle))
                <p class="lead mb-0 text-secondary">
                    {{ $subtitle }}
                </p>
            @endif

        </div>

    </div>
</section>