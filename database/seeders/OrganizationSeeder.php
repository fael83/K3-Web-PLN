<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\Division;
use App\Models\WorkUnit;
use Illuminate\Database\Seeder;

class OrganizationSeeder extends Seeder
{
    public function run(): void
    {
        $divisions = [
            [
                'name' => 'Divisi Operasi & Pemeliharaan',
                'code' => 'OPS',
                'departments' => [
                    ['name' => 'Departemen Transmisi', 'code' => 'TRN', 'units' => ['Unit Gardu Induk', 'Unit SUTET', 'Unit Kontrol']],
                    ['name' => 'Departemen Distribusi', 'code' => 'DST', 'units' => ['Unit Jaringan', 'Unit Pelanggan']],
                ],
            ],
            [
                'name' => 'Divisi K3 & Lingkungan',
                'code' => 'K3L',
                'departments' => [
                    ['name' => 'Departemen K3', 'code' => 'K3', 'units' => ['Unit Inspeksi K3', 'Unit Pelatihan K3']],
                    ['name' => 'Departemen Lingkungan', 'code' => 'LHK', 'units' => ['Unit Limbah', 'Unit Amdal']],
                ],
            ],
            [
                'name' => 'Divisi SDM & Umum',
                'code' => 'SDM',
                'departments' => [
                    ['name' => 'Departemen SDM', 'code' => 'HR', 'units' => ['Unit Rekrutmen', 'Unit Pelatihan']],
                    ['name' => 'Departemen Umum', 'code' => 'GEN', 'units' => ['Unit Logistik', 'Unit Fasilitas']],
                ],
            ],
        ];

        foreach ($divisions as $divData) {
            $division = Division::create([
                'name' => $divData['name'],
                'code' => $divData['code'],
            ]);

            foreach ($divData['departments'] as $deptData) {
                $dept = Department::create([
                    'division_id' => $division->id,
                    'name'        => $deptData['name'],
                    'code'        => $deptData['code'],
                ]);

                foreach ($deptData['units'] as $unitName) {
                    WorkUnit::create([
                        'department_id' => $dept->id,
                        'name'          => $unitName,
                    ]);
                }
            }
        }
    }
}
