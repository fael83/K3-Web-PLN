<?php

namespace App\Exports;

use App\Models\Incident;
use App\Models\User;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class IncidentExport implements FromQuery, WithHeadings, WithMapping, WithStyles, ShouldAutoSize, WithTitle
{
    public function __construct(
        protected array $filters = [],
        protected ?User $user = null
    ) {}

    public function title(): string
    {
        return 'Laporan Insiden';
    }

    public function query()
    {
        $query = Incident::query()->orderByDesc('incident_date');

        // Scope dept_head
        if ($this->user?->role === 'department_head' && $this->user->department_id) {
            $query->where('department_id', $this->user->department_id);
        }

        if (!empty($this->filters['from'])) {
            $query->whereDate('incident_date', '>=', $this->filters['from']);
        }
        if (!empty($this->filters['to'])) {
            $query->whereDate('incident_date', '<=', $this->filters['to']);
        }
        if (!empty($this->filters['status'])) {
            $query->where('status', $this->filters['status']);
        }

        return $query;
    }

    public function headings(): array
    {
        return [
            '#', 'Judul', 'Jenis', 'Lokasi', 'Tanggal',
            'Status', 'Tindak Korektif', 'Dicatat',
        ];
    }

    public function map($incident): array
    {
        return [
            $incident->id,
            $incident->title,
            Incident::TYPES[$incident->incident_type] ?? $incident->incident_type,
            $incident->location ?? '-',
            optional($incident->incident_date)->format('d/m/Y') ?? '-',
            Incident::STATUSES[$incident->status] ?? $incident->status,
            $incident->corrective_action ? 'Ada' : 'Belum Ada',
            optional($incident->created_at)->format('d/m/Y H:i'),
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => [
                'font'      => ['bold' => true, 'color' => ['argb' => 'FFFFFFFF']],
                'fill'      => ['fillType' => 'solid', 'startColor' => ['argb' => 'FF14489A']],
                'alignment' => ['horizontal' => 'center'],
            ],
        ];
    }
}