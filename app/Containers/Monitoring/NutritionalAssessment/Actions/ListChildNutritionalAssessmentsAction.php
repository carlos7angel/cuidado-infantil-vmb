<?php

namespace App\Containers\Monitoring\NutritionalAssessment\Actions;

use App\Containers\Monitoring\Child\Data\Repositories\ChildRepository;
use App\Containers\Monitoring\NutritionalAssessment\Tasks\ListChildNutritionalAssessmentsTask;
use App\Containers\Monitoring\NutritionalAssessment\UI\API\Requests\ListChildNutritionalAssessmentsRequest;
use App\Ship\Parents\Actions\Action as ParentAction;
use App\Ship\Parents\Requests\Request;
use Illuminate\Validation\ValidationException;

final class ListChildNutritionalAssessmentsAction extends ParentAction
{
    public function __construct(
        private readonly ListChildNutritionalAssessmentsTask $listChildNutritionalAssessmentsTask,
        private readonly ChildRepository $childRepository,
    ) {
    }

    public function run(Request $request): mixed
    {
        // Obtener el child_id desde el parámetro de ruta (ya decodificado por Apiato)
        $childId = $request->route('child_id');
        
        if (!$childId) {
            throw new \InvalidArgumentException('child_id is required in the route');
        }
        
        // Verificar que el niño existe
        $this->childRepository->findOrFail($childId);

        return $this->listChildNutritionalAssessmentsTask->run($childId);
    }
}

