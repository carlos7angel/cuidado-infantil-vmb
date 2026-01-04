<?php

namespace App\Containers\Monitoring\ChildVaccination\Data\Seeders;

use App\Containers\Monitoring\ChildVaccination\Models\Vaccine;
use App\Ship\Parents\Seeders\Seeder as ParentSeeder;

final class VaccineSeeder_1 extends ParentSeeder
{
    public function run(): void
    {
        // Edades: recommended = edad ideal, min = mínima permitida, max = máxima permitida (null = sin límite)
        $vaccines = [
            [
                'name' => 'BCG',
                'description' => 'Vacuna contra la tuberculosis',
                'total_doses' => 1,
                'is_required' => true,
                'doses' => [
                    ['dose_number' => 1, 'recommended_age_months' => 0, 'min_age_months' => 0, 'max_age_months' => 12, 'description' => 'Dosis única al nacer'],
                ],
            ],
            [
                'name' => 'Hepatitis B',
                'description' => 'Vacuna contra la Hepatitis B',
                'total_doses' => 1,
                'is_required' => true,
                'doses' => [
                    ['dose_number' => 1, 'recommended_age_months' => 0, 'min_age_months' => 0, 'max_age_months' => 1, 'description' => 'Dosis única al nacer (primeras 24h)'],
                ],
            ],
            [
                'name' => 'Pentavalente',
                'description' => 'Vacuna contra Difteria, Tétanos, Tos ferina, Hepatitis B y Haemophilus influenzae tipo b',
                'total_doses' => 3,
                'is_required' => true,
                'doses' => [
                    ['dose_number' => 1, 'recommended_age_months' => 2, 'min_age_months' => 2, 'max_age_months' => 11, 'description' => 'Primera dosis'],
                    ['dose_number' => 2, 'recommended_age_months' => 4, 'min_age_months' => 3, 'max_age_months' => 11, 'description' => 'Segunda dosis'],
                    ['dose_number' => 3, 'recommended_age_months' => 6, 'min_age_months' => 5, 'max_age_months' => 11, 'description' => 'Tercera dosis'],
                ],
            ],
            [
                'name' => 'Rotavirus',
                'description' => 'Vacuna contra el Rotavirus',
                'total_doses' => 2,
                'is_required' => true,
                'doses' => [
                    ['dose_number' => 1, 'recommended_age_months' => 2, 'min_age_months' => 2, 'max_age_months' => 4, 'description' => 'Primera dosis'],
                    ['dose_number' => 2, 'recommended_age_months' => 4, 'min_age_months' => 4, 'max_age_months' => 8, 'description' => 'Segunda dosis'],
                ],
            ],
            [
                'name' => 'Neumococo',
                'description' => 'Vacuna antineumocócica conjugada',
                'total_doses' => 3,
                'is_required' => true,
                'doses' => [
                    ['dose_number' => 1, 'recommended_age_months' => 2, 'min_age_months' => 2, 'max_age_months' => 11, 'description' => 'Primera dosis'],
                    ['dose_number' => 2, 'recommended_age_months' => 4, 'min_age_months' => 3, 'max_age_months' => 11, 'description' => 'Segunda dosis'],
                    ['dose_number' => 3, 'recommended_age_months' => 12, 'min_age_months' => 12, 'max_age_months' => 23, 'description' => 'Refuerzo'],
                ],
            ],
            [
                'name' => 'Polio (IPV)',
                'description' => 'Vacuna inactivada contra la Poliomielitis',
                'total_doses' => 3,
                'is_required' => true,
                'doses' => [
                    ['dose_number' => 1, 'recommended_age_months' => 2, 'min_age_months' => 2, 'max_age_months' => null, 'description' => 'Primera dosis'],
                    ['dose_number' => 2, 'recommended_age_months' => 4, 'min_age_months' => 3, 'max_age_months' => null, 'description' => 'Segunda dosis'],
                    ['dose_number' => 3, 'recommended_age_months' => 6, 'min_age_months' => 5, 'max_age_months' => null, 'description' => 'Tercera dosis'],
                ],
            ],
            [
                'name' => 'SRP',
                'description' => 'Vacuna contra Sarampión, Rubéola y Paperas',
                'total_doses' => 2,
                'is_required' => true,
                'doses' => [
                    ['dose_number' => 1, 'recommended_age_months' => 12, 'min_age_months' => 12, 'max_age_months' => null, 'description' => 'Primera dosis'],
                    ['dose_number' => 2, 'recommended_age_months' => 18, 'min_age_months' => 15, 'max_age_months' => null, 'description' => 'Refuerzo'],
                ],
            ],
            [
                'name' => 'Fiebre Amarilla',
                'description' => 'Vacuna contra la Fiebre Amarilla',
                'total_doses' => 1,
                'is_required' => true,
                'doses' => [
                    ['dose_number' => 1, 'recommended_age_months' => 12, 'min_age_months' => 12, 'max_age_months' => null, 'description' => 'Dosis única'],
                ],
            ],
            [
                'name' => 'DPT',
                'description' => 'Vacuna contra Difteria, Tétanos y Tos ferina (refuerzo)',
                'total_doses' => 2,
                'is_required' => true,
                'doses' => [
                    ['dose_number' => 1, 'recommended_age_months' => 18, 'min_age_months' => 15, 'max_age_months' => null, 'description' => 'Primer refuerzo'],
                    ['dose_number' => 2, 'recommended_age_months' => 48, 'min_age_months' => 48, 'max_age_months' => 59, 'description' => 'Segundo refuerzo'],
                ],
            ],
        ];

        foreach ($vaccines as $vaccineData) {
            $doses = $vaccineData['doses'];
            unset($vaccineData['doses']);

            $vaccine = Vaccine::firstOrCreate(
                ['name' => $vaccineData['name']],
                $vaccineData
            );

            foreach ($doses as $doseData) {
                $vaccine->doses()->firstOrCreate(
                    [
                        'vaccine_id' => $vaccine->id,
                        'dose_number' => $doseData['dose_number'],
                    ],
                    $doseData
                );
            }
        }
    }
}
