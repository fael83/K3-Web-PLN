<?php

namespace Database\Seeders;

use App\Models\TeamMember;
use Illuminate\Database\Seeder;

class TeamMemberSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            ['Penanggung Jawab K3', 'penanggung_jawab', 'Bertanggung jawab penuh atas penerapan SMK3 di seluruh unit.'],
            ['Ketua P2K3', 'ketua', 'Memimpin Panitia Pembina K3 dan mengarahkan kebijakan keselamatan.'],
            ['Sekretaris P2K3', 'sekretaris', 'Mengelola administrasi, notulen, dan dokumentasi kegiatan K3.'],
            ['Koordinator Identifikasi Bahaya', 'koordinator', 'Memimpin proses HIRADC dan penilaian risiko.'],
            ['Koordinator Tanggap Darurat', 'koordinator', 'Mengoordinasikan prosedur evakuasi dan tim tanggap darurat.'],
            ['Koordinator APD dan Logistik', 'koordinator', 'Memastikan ketersediaan dan kelayakan APD serta logistik K3.'],
            ['Koordinator Kesehatan Kerja', 'koordinator', 'Mengelola program kesehatan, P3K, dan pemeriksaan berkala.'],
            ['Koordinator Pelatihan dan Budaya K3', 'koordinator', 'Menyelenggarakan pelatihan dan membangun budaya keselamatan.'],
            ['Anggota Perwakilan Unit', 'anggota', 'Menjadi penghubung K3 di masing-masing unit operasional.'],
        ];

        foreach ($items as $i => [$position, $level, $resp]) {
            TeamMember::updateOrCreate(
                ['position' => $position],
                ['level' => $level, 'responsibility' => $resp, 'sort_order' => $i + 1]
            );
        }
    }
}
