<?php

namespace Database\Seeders;

use App\Models\Apd;
use Illuminate\Database\Seeder;

class ApdSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            ['name' => 'Helm Keselamatan', 'function' => 'Melindungi kepala dari benturan dan benda jatuh.', 'usage_area' => 'Seluruh area kerja lapangan', 'standard' => 'SNI / ANSI Z89.1'],
            ['name' => 'Sarung Tangan Isolasi', 'function' => 'Melindungi tangan dari sengatan listrik tegangan tinggi.', 'usage_area' => 'Pekerjaan kelistrikan bertegangan', 'standard' => 'IEC 60903 / ASTM D120'],
            ['name' => 'Sepatu Safety', 'function' => 'Melindungi kaki dari benda berat, tajam, dan listrik statis.', 'usage_area' => 'Seluruh area kerja', 'standard' => 'SNI 7079 / ASTM F2413'],
            ['name' => 'Kacamata Pelindung', 'function' => 'Melindungi mata dari percikan api, debu, dan radiasi.', 'usage_area' => 'Pengelasan & pekerjaan mekanik', 'standard' => 'ANSI Z87.1'],
            ['name' => 'Wearpack', 'function' => 'Melindungi tubuh dari panas, percikan, dan kotoran.', 'usage_area' => 'Seluruh area kerja teknis', 'standard' => 'Flame Resistant / Arc Rated'],
            ['name' => 'Full Body Harness', 'function' => 'Mencegah jatuh saat bekerja di ketinggian.', 'usage_area' => 'Tower transmisi & ketinggian', 'standard' => 'EN 361 / ANSI Z359'],
            ['name' => 'Masker dan Pelindung Pernapasan', 'function' => 'Melindungi pernapasan dari debu, gas, dan asap.', 'usage_area' => 'Area berdebu & confined space', 'standard' => 'EN 149 / NIOSH N95'],
            ['name' => 'Pelindung Telinga', 'function' => 'Mengurangi paparan kebisingan pada area mesin.', 'usage_area' => 'Ruang pembangkit & genset', 'standard' => 'EN 352 / ANSI S3.19'],
        ];

        foreach ($items as $item) {
            Apd::updateOrCreate(['name' => $item['name']], array_merge($item, ['is_active' => true]));
        }
    }
}
