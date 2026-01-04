<?php

namespace App\Containers\Monitoring\ChildVaccination\UI\API\Transformers;

use App\Containers\Monitoring\ChildVaccination\Models\ChildVaccination;
use App\Ship\Parents\Transformers\Transformer as ParentTransformer;

final class ChildVaccinationTransformer extends ParentTransformer
{
    protected array $defaultIncludes = [];

    protected array $availableIncludes = [];

    public function transform(ChildVaccination $childVaccination): array
    {
        // Cargar relaciones si no están cargadas
        $childVaccination->loadMissing(['vaccineDose.vaccine', 'child']);

        $vaccineDose = $childVaccination->vaccineDose;
        $vaccine = $vaccineDose->vaccine;
        $child = $childVaccination->child;

        // Calcular edad del niño al momento de la aplicación
        $ageAtApplication = $child->birth_date->diffInMonths($childVaccination->date_applied);

        return [
            'type' => $childVaccination->getResourceKey(),
            'id' => $childVaccination->getHashedKey(),
            'child' => [
                'id' => $child->getHashedKey(),
                'name' => $child->full_name,
            ],
            'vaccine' => [
                'id' => $vaccine->getHashedKey(),
                'name' => $vaccine->name,
                'description' => $vaccine->description,
            ],
            'dose' => [
                'id' => $vaccineDose->getHashedKey(),
                'dose_number' => $vaccineDose->dose_number,
                'recommended_age_months' => $vaccineDose->recommended_age_months,
                'recommended_age_readable' => $vaccineDose->recommended_age,
                'age_range_readable' => $vaccineDose->age_range,
            ],
            'date_applied' => $childVaccination->date_applied->format('Y-m-d'),
            'applied_at' => $childVaccination->applied_at,
            'notes' => $childVaccination->notes,
            'age_at_application_months' => $ageAtApplication,
            'age_at_application_readable' => $this->formatAgeReadable($ageAtApplication),
            'created_at' => $childVaccination->created_at,
            'updated_at' => $childVaccination->updated_at,
            'readable_created_at' => $childVaccination->created_at->diffForHumans(),
            'readable_updated_at' => $childVaccination->updated_at->diffForHumans(),
        ];
    }

    /**
     * Format age in months to human-readable string.
     */
    private function formatAgeReadable(int $months): string
    {
        if ($months === 0) {
            return 'al nacer';
        }

        if ($months < 12) {
            return "{$months} " . ($months === 1 ? 'mes' : 'meses');
        }

        $years = intdiv($months, 12);
        $remainingMonths = $months % 12;

        if ($remainingMonths === 0) {
            return "{$years} " . ($years === 1 ? 'año' : 'años');
        }

        return "{$years} " . ($years === 1 ? 'año' : 'años') . " y {$remainingMonths} " . ($remainingMonths === 1 ? 'mes' : 'meses');
    }
}
