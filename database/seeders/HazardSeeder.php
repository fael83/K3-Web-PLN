<?php

namespace Database\Seeders;

use App\Models\Hazard;
use Illuminate\Database\Seeder;

class HazardSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            ['fisik_mekanik', 'Sengatan Listrik', 'Kontak dengan konduktor bertegangan.', 'Gardu Induk', 'tinggi', 'tinggi', 'Penerapan LOTO, grounding, dan APD isolasi.'],
            ['fisik_mekanik', 'Kebisingan Mesin', 'Paparan bising dari genset dan turbin.', 'Ruang Pembangkit', 'sedang', 'sedang', 'Penggunaan ear plug/ear muff dan rotasi kerja.'],
            ['kimia', 'Paparan Gas SF6', 'Kebocoran gas isolasi pada switchgear.', 'GIS', 'rendah', 'tinggi', 'Ventilasi, deteksi gas, dan APD pernapasan.'],
            ['kimia', 'Tumpahan Oli Trafo', 'Risiko terpeleset dan pencemaran.', 'Area Trafo', 'sedang', 'sedang', 'Bund wall, penampung tumpahan, dan SOP penanganan.'],
            ['biomekanik', 'Pengangkatan Beban Berat', 'Mengangkat material instalasi secara manual.', 'Lapangan', 'sedang', 'sedang', 'Pelatihan manual handling dan alat bantu angkat.'],
            ['lingkungan', 'Bekerja di Ketinggian', 'Pekerjaan pada tower transmisi.', 'Tower SUTET', 'sedang', 'tinggi', 'Full body harness dan sistem proteksi jatuh.'],
            ['lingkungan', 'Cuaca Ekstrem', 'Hujan, petir, dan panas saat kerja lapangan.', 'Jaringan Distribusi', 'sedang', 'sedang', 'Penjadwalan kerja dan penghentian saat petir.'],
            ['psikologi_sosial', 'Kelelahan Kerja Shift', 'Penurunan kewaspadaan akibat shift malam.', 'Operasi 24 Jam', 'sedang', 'sedang', 'Pengaturan shift, waktu istirahat, dan fatigue management.'],
        ];

        foreach ($items as [$cat, $name, $desc, $loc, $like, $sev, $ctrl]) {
            Hazard::updateOrCreate(
                ['category' => $cat, 'name' => $name],
                ['description' => $desc, 'location' => $loc, 'likelihood' => $like, 'severity' => $sev, 'control_measure' => $ctrl]
            );
        }
    }
}
