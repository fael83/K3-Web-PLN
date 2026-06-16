<?php

namespace Database\Seeders;

use App\Models\SopStep;
use Illuminate\Database\Seeder;

class SopStepSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            'sebelum' => [
                ['Briefing K3 (Safety Talk)', 'Lakukan briefing keselamatan dan pembagian tugas sebelum memulai pekerjaan.'],
                ['Periksa Kelengkapan APD', 'Pastikan seluruh APD sesuai jenis pekerjaan tersedia dan layak pakai.'],
                ['Pengajuan Working Permit', 'Ajukan dan pastikan izin kerja (working permit) telah disetujui pengawas.'],
                ['Identifikasi Bahaya (JSA)', 'Lakukan Job Safety Analysis untuk mengidentifikasi potensi bahaya.'],
            ],
            'saat' => [
                ['Terapkan LOTO', 'Lakukan Lock Out Tag Out pada sumber energi sebelum bekerja.'],
                ['Pastikan Bebas Tegangan', 'Uji dan pastikan instalasi sudah bebas tegangan sebelum disentuh.'],
                ['Pasang Grounding', 'Pasang pembumian (grounding) untuk pengamanan tambahan.'],
                ['Patuhi Prosedur Kerja', 'Bekerja sesuai instruksi kerja dan jaga komunikasi dengan tim.'],
            ],
            'pembangkitan' => [
                ['Isolasi Unit Pembangkit', 'Isolasi unit yang akan dikerjakan dari sistem operasi.'],
                ['Kendalikan Energi Panas & Uap', 'Pastikan sistem uap, bahan bakar, dan panas dalam kondisi aman.'],
                ['Pemantauan Gas Berbahaya', 'Lakukan gas test sebelum masuk ruang turbin/boiler.'],
            ],
            'transmisi' => [
                ['Koordinasi dengan Dispatcher', 'Koordinasikan rencana kerja PDKB dengan pusat pengatur beban.'],
                ['Metode Kerja PDKB Sesuai Standar', 'Terapkan metode Pekerjaan Dalam Keadaan Bertegangan sesuai SOP PDKB.'],
                ['Gunakan Peralatan Berisolasi', 'Pastikan seluruh alat kerja terisolasi dan teruji.'],
                ['Jaga Jarak Aman Bertegangan', 'Pertahankan jarak aman minimum sesuai level tegangan.'],
            ],
            'setelah' => [
                ['Lepas Grounding & LOTO', 'Lepaskan pembumian dan LOTO sesuai urutan yang benar.'],
                ['Rapikan Area Kerja', 'Bersihkan dan kembalikan area kerja ke kondisi aman.'],
                ['Laporan Penyelesaian Kerja', 'Buat laporan penyelesaian dan tutup working permit.'],
                ['Evaluasi Pekerjaan', 'Lakukan evaluasi pelaksanaan dan catat temuan K3.'],
            ],
        ];

        foreach ($data as $sector => $steps) {
            foreach ($steps as $i => [$title, $desc]) {
                SopStep::updateOrCreate(
                    ['sector' => $sector, 'title' => $title],
                    ['step_order' => $i + 1, 'description' => $desc]
                );
            }
        }
    }
}
