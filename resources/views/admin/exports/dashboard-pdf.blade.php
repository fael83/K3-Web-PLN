<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: 'Helvetica', sans-serif; font-size: 11px; color: #333; margin: 0; padding: 20px; }
        h1 { font-size: 17px; color: #14489A; margin-bottom: 4px; }
        .meta { font-size: 10px; color: #666; border-bottom: 2px solid #14489A; padding-bottom: 8px; margin-bottom: 20px; }
        .grid { display: table; width: 100%; margin-bottom: 24px; }
        .row  { display: table-row; }
        .cell { display: table-cell; width: 25%; padding: 4px 8px 4px 0; vertical-align: top; }
        .kpi-box { border: 1px solid #e0e0e0; border-radius: 6px; padding: 12px; text-align: center; }
        .kpi-num  { font-size: 28px; font-weight: bold; margin-bottom: 4px; }
        .kpi-label { font-size: 9px; color: #777; text-transform: uppercase; letter-spacing: .05em; }
        .danger  { color: #B42323; }
        .warning { color: #e65100; }
        .success { color: #1c7a43; }
        .primary { color: #14489A; }
        h2 { font-size: 13px; color: #14489A; border-bottom: 1px solid #e0e0e0; padding-bottom: 4px; margin: 20px 0 10px; }
        table { width: 100%; border-collapse: collapse; }
        th { background: #14489A; color: #fff; padding: 6px 8px; text-align: left; font-size: 10px; }
        td { padding: 5px 8px; border-bottom: 1px solid #eee; font-size: 10px; }
        tr:nth-child(even) td { background: #f7f9fc; }
        .footer { margin-top: 24px; font-size: 9px; color: #aaa; text-align: right; border-top: 1px solid #eee; padding-top: 6px; }
        .status-open        { color: #B42323; font-weight: bold; }
        .status-investigasi { color: #e65100; font-weight: bold; }
        .status-selesai     { color: #1c7a43; font-weight: bold; }
    </style>
</head>
<body>

    <h1>&#x1F6E1; Ringkasan K3 — PT PLN (Persero)</h1>
    <div class="meta">
        Diekspor: <strong>{{ $exportedAt }}</strong>
        &nbsp;·&nbsp; Sistem Informasi K3 PLN
    </div>

    {{-- KPI Utama --}}
    <h2>KPI Keselamatan</h2>
    <div class="grid">
        <div class="row">
            <div class="cell">
                <div class="kpi-box">
                    <div class="kpi-num {{ $openIncidents > 0 ? 'danger' : 'success' }}">{{ $openIncidents }}</div>
                    <div class="kpi-label">Insiden Terbuka</div>
                </div>
            </div>
            <div class="cell">
                <div class="kpi-box">
                    <div class="kpi-num {{ $openCapa > 0 ? 'warning' : 'success' }}">{{ $openCapa }}</div>
                    <div class="kpi-label">CAPA Belum Selesai</div>
                </div>
            </div>
            <div class="cell">
                <div class="kpi-box">
                    <div class="kpi-num primary">{{ $totalHazards }}</div>
                    <div class="kpi-label">Potensi Bahaya</div>
                </div>
            </div>
            <div class="cell">
                <div class="kpi-box">
                    <div class="kpi-num primary">{{ $auditSummary['total'] }}</div>
                    <div class="kpi-label">Total Audit</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Audit Summary --}}
    <h2>Ringkasan Audit</h2>
    <table>
        <thead>
            <tr>
                <th>Status</th>
                <th>Jumlah</th>
                <th>Persentase</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Selesai</td>
                <td>{{ $auditSummary['completed'] }}</td>
                <td>
                    @if($auditSummary['total'] > 0)
                        {{ round($auditSummary['completed'] / $auditSummary['total'] * 100) }}%
                    @else — @endif
                </td>
            </tr>
            <tr>
                <td>Sedang Berlangsung</td>
                <td>{{ $auditSummary['total'] - $auditSummary['completed'] }}</td>
                <td>
                    @if($auditSummary['total'] > 0)
                        {{ round(($auditSummary['total'] - $auditSummary['completed']) / $auditSummary['total'] * 100) }}%
                    @else — @endif
                </td>
            </tr>
            <tr>
                <td><strong>Total</strong></td>
                <td><strong>{{ $auditSummary['total'] }}</strong></td>
                <td><strong>100%</strong></td>
            </tr>
        </tbody>
    </table>

    <div class="footer">Sistem Informasi K3 · PT PLN (Persero) · Dicetak: {{ $exportedAt }}</div>

</body>
</html>