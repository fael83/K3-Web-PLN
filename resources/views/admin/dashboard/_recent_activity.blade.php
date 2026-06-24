<div class="card-panel p-4 h-100">
    <h6 class="fw-bold mb-3">
        <i class="bi bi-clock-history text-primary me-2"></i>Aktivitas Terbaru
    </h6>
    <div class="d-flex flex-column gap-2">
        @forelse($recentAuditLogs as $log)
        <div class="d-flex align-items-start gap-2">
            <span class="badge bg-secondary-subtle text-secondary-emphasis mt-1" style="font-size:.65rem;">
                {{ strtoupper(substr($log->action ?? 'ACT', 0, 3)) }}
            </span>
            <div class="flex-grow-1 min-w-0">
                <div class="small text-truncate">{{ $log->description ?? $log->action ?? '-' }}</div>
                <div class="text-muted" style="font-size:.7rem;">
                    {{ $log->user?->name ?? 'System' }} · {{ $log->created_at?->diffForHumans() }}
                </div>
            </div>
        </div>
        @empty
        <div class="text-muted small text-center py-3">
            <i class="bi bi-clock-history d-block mb-1" style="font-size:1.5rem;"></i>
            Belum ada aktivitas
        </div>
        @endforelse
    </div>
</div>