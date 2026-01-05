<?php

namespace App\Containers\Monitoring\ChildDevelopment\Actions;

use App\Containers\Monitoring\Child\Data\Repositories\ChildRepository;
use App\Containers\Monitoring\ChildDevelopment\Tasks\GetApplicableDevelopmentItemsTask;
use App\Containers\Monitoring\ChildDevelopment\UI\API\Requests\GetApplicableDevelopmentItemsRequest;
use App\Ship\Parents\Actions\Action as ParentAction;
use Illuminate\Validation\ValidationException;

final class GetApplicableDevelopmentItemsAction extends ParentAction
{
    public function __construct(
        private readonly GetApplicableDevelopmentItemsTask $getApplicableDevelopmentItemsTask,
        private readonly ChildRepository $childRepository,
    ) {
    }

    public function run(GetApplicableDevelopmentItemsRequest $request): array
    {
        // Obtener el child_id desde el parámetro de ruta (ya decodificado por Apiato)
        $childId = $request->route('child_id');
        
        if (!$childId) {
            throw new \InvalidArgumentException('child_id is required in the route');
        }
        
        // Obtener el niño
        $child = $this->childRepository->findOrFail($childId);

        // Validar que el niño tenga fecha de nacimiento definida
        if (!$child->birth_date) {
            throw ValidationException::withMessages([
                'child_id' => ['El niño debe tener una fecha de nacimiento definida para obtener los ítems de desarrollo aplicables.'],
            ]);
        }

        // Calcular la edad en meses al momento de la evaluación
        $evaluationDate = $request->input('evaluation_date') 
            ? \Carbon\Carbon::parse($request->input('evaluation_date'))
            : now();
        
        $ageMonths = $child->age_in_months;

        return $this->getApplicableDevelopmentItemsTask->run($ageMonths, $child);
    }
}

