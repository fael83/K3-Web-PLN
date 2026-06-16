<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 — Akses Ditolak</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="{{ asset('assets/app.css') }}" rel="stylesheet">
</head>
<body style="background: var(--surface); min-height:100vh; display:flex; align-items:center; justify-content:center;">
<div class="text-center p-4">
    <div class="card-icon warn mx-auto mb-4" style="width:80px;height:80px;font-size:2.5rem;">
        <i class="bi bi-shield-x"></i>
    </div>
    <h1 class="display-font fw-bold mb-2" style="color:var(--pln-blue);font-size:5rem;">403</h1>
    <h4 class="fw-bold mb-2">Akses Ditolak</h4>
    <p class="text-muted mb-4">Anda tidak memiliki izin untuk mengakses halaman ini.</p>
    <a href="{{ url('/') }}" class="btn btn-pln px-4">
        <i class="bi bi-house me-1"></i> Kembali ke Beranda
    </a>
</div>
</body>
</html>
