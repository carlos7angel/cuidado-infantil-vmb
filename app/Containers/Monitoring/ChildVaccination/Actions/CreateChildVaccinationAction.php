<?php

namespace App\Containers\Monitoring\ChildVaccination\Actions;

use App\Containers\Monitoring\ChildVaccination\Models\ChildVaccination;
use App\Containers\Monitoring\ChildVaccination\Tasks\CreateChildVaccinationTask;
use App\Containers\Monitoring\ChildVaccination\UI\API\Requests\CreateChildVaccinationRequest;
use App\Ship\Parents\Actions\Action as ParentAction;

final class CreateChildVaccinationAction extends ParentAction
{
    public function __construct(
        private readonly CreateChildVaccinationTask $createChildVaccinationTask,
    ) {
    }

    public function run(CreateChildVaccinationRequest $request): ChildVaccination
    {
        // Obtener child_id desde la ruta si está disponible, sino desde el input
        $childId = $request->route('child_id') ?? $request->input('child_id');

        $data = $request->sanitize([
            'child_id' => $childId,
            'vaccine_dose_id' => $request->input('vaccine_dose_id'),
            'date_applied' => $request->input('date_applied'),
            'applied_at' => $request->input('applied_at'),
            'notes' => $request->input('notes'),
        ]);

        return $this->createChildVaccinationTask->run($data);
    }
}
