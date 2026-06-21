<?php

namespace Database\Seeders;

use App\Models\AuditChecklist;
use App\Models\AuditItem;
use App\Models\User;
use Illuminate\Database\Seeder;

class AuditChecklistSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('role', 'sys_admin')->first() ?? User::first();

        // Audit 1: SMK3 Semester 1 2026 (completed)
        $audit1 = AuditChecklist::create([
            'title'        => 'Audit SMK3 Semester I 2026',
            'description'  => 'Audit sistem manajemen K3 periode Januari–Juni 2026',
            'audit_type'   => 'smk3',
            'audit_date'   => '2026-06-15',
            'auditor_name' => 'Tim Auditor Internal PLN',
            'status'       => 'completed',
            'created_by'   => $admin?->id,
        ]);

        $items1 = [
            ['item_name' => 'SOP K3 tersedia dan mutakhir', 'status' => 'conformance', 'finding' => 'SOP sudah diperbarui dan tersedia di sistem.'],
            ['item_name' => 'APD tersedia dan layak pakai', 'status' => 'minor_nc', 'finding' => 'Stok sarung tangan kurang dari standar minimal.', 'corrective_action' => 'Segera pengadaan sarung tangan sebanyak 50 pasang.'],
            ['item_name' => 'Pelatihan K3 telah dilaksanakan', 'status' => 'conformance', 'finding' => 'Pelatihan K3 dilaksanakan Maret 2026 dengan 85% kehadiran.'],
            ['item_name' => 'Laporan insiden tercatat', 'status' => 'conformance', 'finding' => 'Semua insiden tercatat di sistem.'],
            ['item_name' => 'Inspeksi rutin K3 dilakukan', 'status' => 'observation', 'finding' => 'Inspeksi dilakukan namun tidak selalu terdokumentasi secara digital.'],
            ['item_name' => 'Identifikasi bahaya terdokumentasi', 'status' => 'conformance', 'finding' => 'IBPR sudah diperbarui tahun 2026.'],
            ['item_name' => 'Tindakan perbaikan (CAPA) dilaksanakan', 'status' => 'major_nc', 'finding' => '3 temuan audit 2025 belum ditindaklanjuti.', 'corrective_action' => 'Selesaikan CAPA paling lambat 30 Juli 2026.'],
            ['item_name' => 'Program kesehatan karyawan berjalan', 'status' => 'conformance', 'finding' => 'MCU dilaksanakan Februari 2026.'],
            ['item_name' => 'Prosedur evakuasi darurat tersedia', 'status' => 'conformance', 'finding' => 'Denah dan SOP evakuasi terpasang di lokasi strategis.'],
            ['item_name' => 'Tim K3 aktif dan berstruktur', 'status' => 'conformance', 'finding' => 'P2K3 aktif dengan rapat bulanan rutin.'],
        ];

        foreach ($items1 as $i => $item) {
            AuditItem::create(array_merge([
                'audit_id'   => $audit1->id,
                'sort_order' => $i,
                'description'=> null,
                'corrective_action' => null,
                'evidence_ref' => null,
            ], $item));
        }

        // Audit 2: Internal Draft
        $audit2 = AuditChecklist::create([
            'title'        => 'Audit Internal Q3 2026',
            'description'  => 'Persiapan audit internal kuartal ketiga 2026',
            'audit_type'   => 'internal',
            'audit_date'   => '2026-09-10',
            'auditor_name' => 'Budi Santoso (K3 Manager)',
            'status'       => 'draft',
            'created_by'   => $admin?->id,
        ]);

        $items2 = [
            ['item_name' => 'SOP K3 tersedia dan mutakhir'],
            ['item_name' => 'APD tersedia dan layak pakai'],
            ['item_name' => 'Pelatihan K3 telah dilaksanakan'],
            ['item_name' => 'Laporan insiden tercatat'],
            ['item_name' => 'Inspeksi rutin K3 dilakukan'],
        ];

        foreach ($items2 as $i => $item) {
            AuditItem::create([
                'audit_id'   => $audit2->id,
                'item_name'  => $item['item_name'],
                'status'     => 'pending',
                'sort_order' => $i,
            ]);
        }
    }
}
