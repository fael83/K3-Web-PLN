<?php

namespace Database\Seeders;

use App\Models\HealthProgram;
use Illuminate\Database\Seeder;

class HealthProgramSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            ['Pemeriksaan Kesehatan Awal', 'Pemeriksaan kesehatan sebelum penempatan kerja (pre-employment).'],
            ['Pemeriksaan Kesehatan Berkala', 'Medical check up rutin untuk memantau kondisi kesehatan pekerja.'],
            ['Pemeriksaan Kesehatan Khusus', 'Pemeriksaan bagi pekerja dengan risiko paparan khusus.'],
            ['Manajemen P3K', 'Penyediaan kotak P3K dan pelatihan pertolongan pertama.'],
            ['Vaksinasi Berbasis Risiko', 'Program imunisasi sesuai risiko paparan di tempat kerja.'],
            ['Pencegahan Kelelahan dan Shift', 'Pengaturan jam kerja dan fatigue management.'],
            ['Ergonomi', 'Penyesuaian stasiun kerja untuk mencegah gangguan otot rangka.'],
            ['Kesehatan Mental dan Psikososial', 'Dukungan psikologis dan manajemen stres kerja.'],
            ['Monitoring Lingkungan Kerja', 'Pengukuran kebisingan, pencahayaan, dan kualitas udara.'],
            ['Pelaporan Digital dan Tindak Lanjut', 'Sistem pelaporan kesehatan kerja berbasis digital.'],
        ];

        foreach ($items as $i => [$name, $desc]) {
            HealthProgram::updateOrCreate(
                ['program_name' => $name],
                ['description' => $desc, 'sort_order' => $i + 1]
            );
        }
    }
}
