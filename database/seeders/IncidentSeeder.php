<?php

namespace Database\Seeders;

use App\Models\Incident;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class IncidentSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            ['near_miss', 'Hampir Tersengat saat Pemeliharaan', 'Pekerja hampir menyentuh konduktor yang belum dibebaskan tegangannya.', 'Gardu Induk Kediri', Carbon::now()->subMonths(5), 'selesai', 'Penegasan prosedur LOTO dan verifikasi bebas tegangan.'],
            ['kecelakaan_ringan', 'Tergores Saat Penarikan Kabel', 'Tangan pekerja tergores ujung kabel tanpa sarung tangan.', 'Distribusi Pare', Carbon::now()->subMonths(4), 'selesai', 'Wajib sarung tangan kerja dan briefing ulang.'],
            ['kebakaran', 'Percikan Api pada Panel', 'Terjadi percikan akibat koneksi longgar pada panel distribusi.', 'Gardu Distribusi 12', Carbon::now()->subMonths(3), 'investigasi', 'Pemeriksaan koneksi dan thermografi panel.'],
            ['near_miss', 'Material Jatuh dari Tower', 'Kunci jatuh dari ketinggian, tidak ada korban.', 'Tower SUTT 70kV', Carbon::now()->subMonths(2), 'selesai', 'Penggunaan tool lanyard wajib di ketinggian.'],
            ['kecelakaan_ringan', 'Terpeleset Tumpahan Oli', 'Pekerja terpeleset di area trafo.', 'Area Trafo GI', Carbon::now()->subMonth(), 'open', null],
        ];

        foreach ($items as [$type, $title, $desc, $loc, $date, $status, $action]) {
            Incident::updateOrCreate(
                ['title' => $title],
                [
                    'incident_type'     => $type,
                    'description'       => $desc,
                    'location'          => $loc,
                    'incident_date'     => $date,
                    'status'            => $status,
                    'corrective_action' => $action,
                ]
            );
        }
    }
}
