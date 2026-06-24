<div class="card-panel p-4 h-100">
    <h6 class="fw-bold mb-3">
        <i class="bi bi-clipboard-check text-primary me-2"></i>Ringkasan Audit
    </h6>
    <div class="row g-2 text-center">
        <div class="col-6">
            <div class="border rounded p-2">
                <div class="h4 fw-bold text-success mb-0">{{ $auditSummary['completed'] }}</div>
                <div class="small text-muted">Selesai</div>
            </div>
        </div>
        <div class="col-6">
            <div class="border rounded p-2">
                <div class="h4 fw-bold text-warning mb-0">{{ $auditSummary['in_progress'] }}</div>
                <div class="small text-muted">Berlangsung</div>
            </div>
        </div>
        <div class="col-6">
            <div class="border rounded p-2">
                <div class="h4 fw-bold text-secondary mb-0">{{ $auditSummary['draft'] }}</div>
                <div class="small text-muted">Draft</div>
            </div>
        </div>
        <div class="col-6">
            <div class="border rounded p-2">
                <div class="h4 fw-bold mb-0">{{ $auditSummary['total'] }}</div>
                <div class="small text-muted">Total</div>
            </div>
        </div>
    </div>
    <div class="mt-3">
        @if($openFindings > 0)
        <div class="alert alert-warning py-2 px-3 small mb-0">
            <i class="bi bi-exclamation-triangle me-1"></i>
            <strong>{{ $openFindings }}</strong> temuan belum ditindaklanjuti.
            <a href="{{ route('admin.audit-checklist.index') }}" class="alert-link">Lihat →</a>
        </div>
        @else
        <div class="alert alert-success py-2 px-3 small mb-0">
            <i class="bi bi-check-circle me-1"></i>Semua temuan sudah ditindaklanjuti.
        </div>
        @endif
    </div>
</div>