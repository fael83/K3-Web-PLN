<?php

namespace Database\Seeders;

use App\Models\TeamMember;
use Illuminate\Database\Seeder;

class TeamMemberSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            ['jabatan' => 'Penanggung Jawab K3',             'nama' => 'Nama Penanggung Jawab',       'responsibility' => 'Bertanggung jawab penuh atas penerapan SMK3 di seluruh unit.',          'sort_order' => 1],
            ['jabatan' => 'Ketua P2K3',                      'nama' => 'Nama Ketua P2K3',             'responsibility' => 'Memimpin Panitia Pembina K3 dan mengarahkan kebijakan keselamatan.',    'sort_order' => 2],
            ['jabatan' => 'Sekretaris P2K3',                 'nama' => 'Nama Sekretaris',             'responsibility' => 'Mengelola administrasi, notulen, dan dokumentasi kegiatan K3.',         'sort_order' => 3],
            ['jabatan' => 'Koordinator Identifikasi Bahaya', 'nama' => 'Nama Koordinator',            'responsibility' => 'Memimpin proses HIRADC dan penilaian risiko.',                          'sort_order' => 4],
            ['jabatan' => 'Koordinator Tanggap Darurat',     'nama' => 'Nama Koordinator',            'responsibility' => 'Mengoordinasikan prosedur evakuasi dan tim tanggap darurat.',           'sort_order' => 5],
            ['jabatan' => 'Koordinator APD dan Logistik',    'nama' => 'Nama Koordinator',            'responsibility' => 'Memastikan ketersediaan dan kelayakan APD serta logistik K3.',         'sort_order' => 6],
            ['jabatan' => 'Koordinator Kesehatan Kerja',     'nama' => 'Nama Koordinator',            'responsibility' => 'Mengelola program kesehatan, P3K, dan pemeriksaan berkala.',            'sort_order' => 7],
            ['jabatan' => 'Koordinator Pelatihan K3',        'nama' => 'Nama Koordinator',            'responsibility' => 'Menyelenggarakan pelatihan dan membangun budaya keselamatan.',          'sort_order' => 8],
            ['jabatan' => 'Anggota Perwakilan Unit',         'nama' => 'Nama Anggota',                'responsibility' => 'Menjadi penghubung K3 di masing-masing unit operasional.',             'sort_order' => 9],
        ];

        foreach ($items as $item) {
            TeamMember::updateOrCreate(
                ['jabatan' => $item['jabatan']],
                [
                    'nama'           => $item['nama'],
                    'responsibility' => $item['responsibility'],
                    'sort_order'     => $item['sort_order'],
                    'status'         => 'active',
                ]
            );
        }
    }
}