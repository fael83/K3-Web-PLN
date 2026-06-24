<div class="mt-3 d-flex flex-column gap-1">
    <div class="d-flex justify-content-between small">
        <span class="text-danger"><i class="bi bi-circle-fill me-1" style="font-size:.5rem;"></i>Open</span>
        <strong>{{ collect($statusChart['data'])[0] ?? 0 }}</strong>
    </div>
    <div class="d-flex justify-content-between small">
        <span class="text-warning"><i class="bi bi-circle-fill me-1" style="font-size:.5rem;"></i>Investigasi</span>
        <strong>{{ collect($statusChart['data'])[1] ?? 0 }}</strong>
    </div>
    <div class="d-flex justify-content-between small">
        <span class="text-success"><i class="bi bi-circle-fill me-1" style="font-size:.5rem;"></i>Selesai</span>
        <strong>{{ collect($statusChart['data'])[2] ?? 0 }}</strong>
    </div>
</div>