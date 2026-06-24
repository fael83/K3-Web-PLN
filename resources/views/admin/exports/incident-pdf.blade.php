<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: 'Helvetica', sans-serif; font-size: 11px; color: #333; }
        h1 { font-size: 16px; color: #14489A; margin-bottom: 4px; }
        .meta { font-size: 10px; color: #666; margin-bottom: 16px; }
        table { width: 100%; border-collapse: collapse; }
        th { background: #14489A; color: #fff; padding: 6px 8px; text-align: left; font-size: 10px; }
        td { padding: 5px 8px; border-bottom: 1px solid #eee; font-size: 10px; }
        tr:nth-child(even) td { background: #f7f9fc; }
        .badge-open { color: #B42323; font-weight: bold; }
        .badge-investigasi { color: #e65100; font-weight: bold; }
        .badge-selesai { color: #1c7a43; font-weight: bold; }
        .footer { margin-top: 20px; font-size: 9px; color: #999; text-align: right; }
    </style>
</head>
<body>
    <h1>Laporan Insiden K3 — PLN</h1>
    <div class="meta">
        Departemen: {{ $department }} &nbsp;|&nbsp;
        Diekspor: {{ $exportedAt }} &nbsp;|&nbsp;
        Total: {{ $incidents->count() }} insiden
    </div>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Judul Insiden</th>
                <th>Jenis</th>
                <th>Lokasi</th>
                <th>Tanggal</th>
                <th>Status</th>
                <th>CAPA</th>
            </tr>
        </thead>
        <tbody>
            @forelse($incidents as $i => $item)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>{{ $item->title }}</td>
                <td>{{ \App\Models\Incident::TYPES[$item->incident_type] ?? $item->incident_type }}</td>
                <td>{{ $item->location ?? '—' }}</td>
                <td>{{ optional($item->incident_date)->format('d/m/Y') ?? '—' }}</td>
                <td class="badge-{{ $item->status }}">
                    {{ \App\Models\Incident::STATUSES[$item->status] ?? $item->status }}
                </td>
                <td>{{ $item->corrective_action ? '✓ Ada' : '✗ Belum' }}</td>
            </tr>
            @empty
            <tr><td colspan="7" style="text-align:center;color:#999;">Tidak ada data</td></tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">Sistem Informasi K3 · PT PLN (Persero) · {{ $exportedAt }}</div>
</body>
</html>