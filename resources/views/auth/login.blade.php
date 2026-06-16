<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin — K3 PT PLN (Persero)</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@600;700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <link href="{{ asset('assets/app.css') }}" rel="stylesheet">
</head>
<body style="background: linear-gradient(135deg, var(--pln-blue-dark), var(--pln-blue)); min-height: 100vh;">
<div class="container">
    <div class="row justify-content-center align-items-center" style="min-height: 100vh;">
        <div class="col-md-6 col-lg-5 col-xl-4">
            <div class="text-center text-white mb-4">
                <span class="brand-mark mx-auto mb-3" style="width:56px;height:56px;font-size:1.8rem;"><i class="bi bi-lightning-charge-fill"></i></span>
                <h4 class="fw-bold display-font mb-1">Panel Admin K3 PLN</h4>
                <p class="small text-white-50 mb-0">Sistem Informasi Keselamatan & Kesehatan Kerja</p>
            </div>

            <div class="card border-0 shadow-lg" style="border-radius: var(--radius);">
                <div class="card-body p-4 p-lg-5">
                    <h5 class="fw-bold mb-1">Masuk</h5>
                    <p class="text-muted small mb-4">Silakan masuk untuk mengelola data K3.</p>

                    @if ($errors->any())
                        <div class="alert alert-danger py-2 small">{{ $errors->first() }}</div>
                    @endif

                    <form method="POST" action="{{ route('login.attempt') }}">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label small fw-semibold">Email</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="bi bi-envelope"></i></span>
                                <input type="email" name="email" value="{{ old('email') }}" required autofocus
                                       class="form-control" placeholder="admin@k3pln.com">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-semibold">Kata Sandi</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="bi bi-lock"></i></span>
                                <input type="password" name="password" required class="form-control" placeholder="••••••••">
                            </div>
                        </div>
                        <div class="form-check mb-4">
                            <input type="checkbox" name="remember" class="form-check-input" id="remember">
                            <label class="form-check-label small" for="remember">Ingat saya</label>
                        </div>
                        <button type="submit" class="btn btn-pln w-100 py-2 fw-semibold">
                            <i class="bi bi-box-arrow-in-right me-1"></i> Masuk
                        </button>
                    </form>
                </div>
            </div>

            <div class="text-center mt-4">
                <a href="{{ route('public.home') }}" class="text-white-50 small text-decoration-none">
                    <i class="bi bi-arrow-left me-1"></i> Kembali ke situs publik
                </a>
            </div>
        </div>
    </div>
</div>
</body>
</html>
