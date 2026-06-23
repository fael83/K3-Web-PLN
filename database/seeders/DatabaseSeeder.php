<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            OrganizationSeeder::class,
            AdminUserSeeder::class,
            ApdSeeder::class,
            SopStepSeeder::class,
            HazardSeeder::class,
            IncidentSeeder::class,
            TeamMemberSeeder::class,
            HealthProgramSeeder::class,
            AuditChecklistSeeder::class,
        ]);
    }
}
