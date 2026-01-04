<?php

namespace App\Containers\Monitoring\Educator\Data\Seeders;

use App\Containers\AppSection\User\Enums\Gender;
use App\Containers\Monitoring\Educator\Models\Educator;
use App\Ship\Parents\Seeders\Seeder as ParentSeeder;

final class EducatorSeeder_1 extends ParentSeeder
{
    public function run(): void
    {
        Educator::firstOrCreate(
            ['dni' => '12345678'],
            [
                'first_name' => 'Ana',
                'last_name' => 'López Mamani',
                'gender' => Gender::FEMALE,
                'birth' => '1990-05-15',
                'state' => 'Activo',
                'phone' => '+591 71234567',
            ]
        );
    }
}
