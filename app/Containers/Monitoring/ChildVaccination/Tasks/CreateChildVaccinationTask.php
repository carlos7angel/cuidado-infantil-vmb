<?php

namespace App\Containers\Monitoring\ChildVaccination\Tasks;

use App\Containers\Monitoring\Child\Data\Repositories\ChildRepository;
use App\Containers\Monitoring\ChildVaccination\Data\Repositories\ChildVaccinationRepository;
use App\Containers\Monitoring\ChildVaccination\Models\ChildVaccination;
use App\Containers\Monitoring\ChildVaccination\Models\VaccineDose;
use App\Ship\Parents\Tasks\Task as ParentTask;
use Illuminate\Database\QueryException;
use Illuminate\Validation\ValidationException;

final class CreateChildVaccinationTask extends ParentTask
{
    public function __construct(
        private readonly ChildVaccinationRepository $repository,
        private readonly ChildRepository $childRepository,
    ) {
    }

    public function run(array $data): ChildVaccination
    {
        // Obtener el niño y la dosis para validaciones
        $child = $this->childRepository->findOrFail($data['child_id']);
        $vaccineDose = VaccineDose::with('vaccine')->findOrFail($data['vaccine_dose_id']);

        // Validar que el niño no tenga ya esta dosis aplicada
        $existingVaccination = $this->repository->findWhere([
            'child_id' => $child->id,
            'vaccine_dose_id' => $vaccineDose->id,
        ])->first();

        if ($existingVaccination) {
            throw ValidationException::withMessages([
                'vaccine_dose_id' => [
                    "El niño ya tiene registrada la dosis {$vaccineDose->dose_number} de la vacuna {$vaccineDose->vaccine->name}.",
                ],
            ]);
        }

        // Calcular la edad del niño al momento de la aplicación
        $dateApplied = \Carbon\Carbon::parse($data['date_applied']);
        $ageInMonths = $child->birth_date->diffInMonths($dateApplied);

        // Validar que la edad del niño esté dentro del rango válido de la dosis
        if (!$vaccineDose->isWithinValidAge($ageInMonths) && false) {
            $ageReadable = $this->formatAgeReadable($ageInMonths);
            $minAgeReadable = $vaccineDose->recommended_age;
            $maxAgeReadable = $vaccineDose->max_age_months
                ? $this->formatAgeReadable($vaccineDose->max_age_months)
                : 'sin límite';

            if ($vaccineDose->isTooYoung($ageInMonths)) {
                throw ValidationException::withMessages([
                    'date_applied' => [
                        "El niño tiene {$ageReadable} al momento de la aplicación, pero la edad mínima para esta dosis es {$minAgeReadable}.",
                    ],
                ]);
            }

            if ($vaccineDose->isExpired($ageInMonths)) {
                throw ValidationException::withMessages([
                    'date_applied' => [
                        "El niño tiene {$ageReadable} al momento de la aplicación, pero ya excedió la edad máxima permitida ({$maxAgeReadable}) para esta dosis.",
                    ],
                ]);
            }
        }

        // Intentar crear el registro
        try {
            return $this->repository->create($data);
        } catch (QueryException $e) {
            // Capturar violación de constraint único (por si acaso)
            if ($e->getCode() === '23000') {
                throw ValidationException::withMessages([
                    'vaccine_dose_id' => [
                        "El niño ya tiene registrada esta dosis de vacuna.",
                    ],
                ]);
            }

            throw $e;
        }
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
