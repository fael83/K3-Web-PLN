<?php

namespace App\Http\Controllers;

use App\Models\Apd;
use App\Models\Hazard;
use App\Models\HealthProgram;
use App\Models\SopStep;
use App\Models\TeamMember;

class PublicController extends Controller
{
    public function home()
    {
        $stats = [
            ['label' => 'Kapasitas Terpasang', 'value' => '75.936,46', 'unit' => 'MW', 'icon' => 'bi-lightning-charge-fill'],
            ['label' => 'Jaringan Transmisi', 'value' => '72.656', 'unit' => 'km', 'icon' => 'bi-diagram-3-fill'],
            ['label' => 'Pelanggan', 'value' => '92,8', 'unit' => 'juta', 'icon' => 'bi-people-fill'],
            ['label' => 'Pegawai', 'value' => '38.289', 'unit' => 'orang', 'icon' => 'bi-person-badge-fill'],
        ];

        $menus = [
            ['title' => 'APD', 'desc' => 'Standar alat pelindung diri untuk pekerja kelistrikan.', 'icon' => 'bi-shield-shaded', 'route' => 'public.apd'],
            ['title' => 'SOP Keselamatan', 'desc' => 'Prosedur kerja aman sebelum, saat, dan setelah bekerja.', 'icon' => 'bi-list-check', 'route' => 'public.sop'],
            ['title' => 'Identifikasi Bahaya', 'desc' => 'Lima kategori bahaya di lingkungan kerja PLN.', 'icon' => 'bi-exclamation-triangle', 'route' => 'public.bahaya'],
            ['title' => 'Prosedur Evakuasi', 'desc' => 'Langkah tanggap darurat dan jalur evakuasi.', 'icon' => 'bi-signpost-2', 'route' => 'public.evakuasi'],
            ['title' => 'Struktur Tim K3', 'desc' => 'Organisasi P2K3 dan pembagian tanggung jawab.', 'icon' => 'bi-diagram-2', 'route' => 'public.tim'],
            ['title' => 'Denah Lokasi', 'desc' => 'Tata letak fasilitas, APAR, P3K, dan titik kumpul.', 'icon' => 'bi-map', 'route' => 'public.denah'],
        ];

        return view('public.home', compact('stats', 'menus'));
    }

    public function profil()
    {
        return view('public.profil');
    }

    public function bahaya()
    {
        $categories = [
            'fisik_mekanik' => [
                'title' => 'Fisik dan Mekanik',
                'icon'  => 'bi-gear-wide-connected',
                'desc'  => 'Bahaya dari energi listrik, kebisingan, getaran, suhu ekstrem, serta benda bergerak dan peralatan mekanik.',
            ],
            'kimia' => [
                'title' => 'Kimia',
                'icon'  => 'bi-droplet-half',
                'desc'  => 'Paparan bahan kimia seperti oli trafo, gas SF6, asam baterai, dan bahan pembersih industri.',
            ],
            'biomekanik' => [
                'title' => 'Biomekanik',
                'icon'  => 'bi-person-arms-up',
                'desc'  => 'Postur kerja tidak ergonomis, pengangkatan beban berat, dan gerakan berulang yang memicu cedera otot rangka.',
            ],
            'lingkungan' => [
                'title' => 'Lingkungan',
                'icon'  => 'bi-cloud-haze2',
                'desc'  => 'Cuaca ekstrem, medan kerja sulit, ketinggian, ruang terbatas, serta paparan radiasi medan listrik dan magnet.',
            ],
            'psikologi_sosial' => [
                'title' => 'Psikologi dan Sosial',
                'icon'  => 'bi-emoji-neutral',
                'desc'  => 'Beban kerja, tekanan waktu, kerja shift, kelelahan mental, dan konflik antar individu di tempat kerja.',
            ],
        ];

        $grouped = Hazard::all()->groupBy('category');

        return view('public.bahaya', compact('categories', 'grouped'));
    }

    public function risiko()
    {
        $risks = [
            ['name' => 'Sengatan Listrik', 'icon' => 'bi-lightning', 'desc' => 'Kontak langsung/tidak langsung dengan konduktor bertegangan.'],
            ['name' => 'Kebakaran', 'icon' => 'bi-fire', 'desc' => 'Hubungan pendek, panas berlebih, atau material mudah terbakar.'],
            ['name' => 'Ledakan Listrik / Arc Flash', 'icon' => 'bi-stars', 'desc' => 'Busur listrik berenergi tinggi pada panel dan switchgear.'],
            ['name' => 'Jatuh dari Ketinggian', 'icon' => 'bi-arrow-down-circle', 'desc' => 'Pekerjaan pada tower, tiang, dan struktur tinggi.'],
            ['name' => 'Tertimpa Benda', 'icon' => 'bi-box-arrow-in-down', 'desc' => 'Material atau peralatan jatuh saat pekerjaan berlangsung.'],
            ['name' => 'Kejang Otot', 'icon' => 'bi-activity', 'desc' => 'Akibat postur kerja buruk dan aktivitas fisik berlebih.'],
            ['name' => 'Luka Bakar Percikan Api', 'icon' => 'bi-thermometer-sun', 'desc' => 'Percikan api saat pengelasan atau gangguan listrik.'],
            ['name' => 'Confined Space', 'icon' => 'bi-door-closed', 'desc' => 'Bekerja di ruang terbatas dengan ventilasi minim.'],
            ['name' => 'Unsafe Act & Unsafe Condition', 'icon' => 'bi-exclamation-octagon', 'desc' => 'Tindakan dan kondisi tidak aman sebagai akar kecelakaan.'],
        ];

        return view('public.risiko', compact('risks'));
    }

    public function apd()
    {
        $apds = Apd::active()->orderBy('name')->get();

        return view('public.apd', compact('apds'));
    }

    public function sop()
    {
        $steps = SopStep::orderBy('step_order')->get()->groupBy('sector');
        $sectors = SopStep::SECTORS;

        return view('public.sop', compact('steps', 'sectors'));
    }

    public function evakuasi()
    {
        $steps = [
            ['title' => 'Aktivasi Alarm Darurat', 'desc' => 'Tekan tombol alarm terdekat untuk memberitahu seluruh penghuni gedung.', 'icon' => 'bi-bell'],
            ['title' => 'Hentikan Aktivitas Kerja', 'desc' => 'Segera hentikan pekerjaan dan amankan peralatan yang sedang digunakan.', 'icon' => 'bi-pause-circle'],
            ['title' => 'Putus Sumber Listrik', 'desc' => 'Matikan sumber listrik utama jika aman dilakukan untuk mencegah bahaya lanjutan.', 'icon' => 'bi-plug'],
            ['title' => 'Lakukan Evakuasi', 'desc' => 'Ikuti jalur evakuasi menuju titik kumpul, jangan gunakan lift.', 'icon' => 'bi-signpost-split'],
            ['title' => 'Bantu Korban', 'desc' => 'Bantu rekan yang terluka atau kesulitan tanpa membahayakan diri sendiri.', 'icon' => 'bi-heart-pulse'],
            ['title' => 'Hubungi Tim Darurat', 'desc' => 'Hubungi tim tanggap darurat, pemadam kebakaran, atau layanan medis.', 'icon' => 'bi-telephone'],
            ['title' => 'Pendataan dan Evaluasi', 'desc' => 'Lakukan pendataan personel di titik kumpul dan evaluasi kejadian.', 'icon' => 'bi-clipboard-check'],
        ];

        return view('public.evakuasi', compact('steps'));
    }

    public function kesehatan()
    {
        $programs = HealthProgram::orderBy('sort_order')->get();

        return view('public.kesehatan', compact('programs'));
    }

    public function tim()
    {
        $members = TeamMember::orderBy('sort_order')->get();

        return view('public.tim', compact('members'));
    }

    public function denah()
    {
        $legend = [
            ['label' => 'Customer Service', 'icon' => 'bi-headset'],
            ['label' => 'Lobby', 'icon' => 'bi-door-open'],
            ['label' => 'Ruang Tunggu', 'icon' => 'bi-people'],
            ['label' => 'Ruang Administrasi', 'icon' => 'bi-folder'],
            ['label' => 'Ruang Teknis', 'icon' => 'bi-tools'],
            ['label' => 'Kotak P3K', 'icon' => 'bi-bandaid'],
            ['label' => 'APAR', 'icon' => 'bi-fire'],
            ['label' => 'Pintu Masuk', 'icon' => 'bi-box-arrow-in-right'],
            ['label' => 'Area Parkir', 'icon' => 'bi-p-square'],
            ['label' => 'Titik Kumpul', 'icon' => 'bi-geo-alt'],
        ];

        return view('public.denah', compact('legend'));
    }

    public function kesimpulan()
    {
        $saran = [
            'Menerapkan budaya keselamatan kerja (safety culture) di seluruh lini operasional secara konsisten.',
            'Melakukan identifikasi bahaya dan penilaian risiko (HIRADC) secara berkala dan terdokumentasi.',
            'Memastikan ketersediaan dan penggunaan APD yang sesuai standar bagi setiap pekerja.',
            'Menyelenggarakan pelatihan K3 dan simulasi tanggap darurat secara rutin.',
            'Memperkuat sistem pelaporan insiden dan near miss berbasis digital.',
            'Melakukan inspeksi dan pemeliharaan peralatan kelistrikan secara terjadwal.',
            'Menjaga kesehatan pekerja melalui pemeriksaan berkala dan program kesehatan kerja.',
            'Mematuhi standar SMK3 dan sertifikasi ISO 45001:2018 secara berkelanjutan.',
            'Melakukan evaluasi dan perbaikan berkelanjutan (continuous improvement) terhadap sistem K3.',
        ];

        return view('public.kesimpulan', compact('saran'));
    }
}
