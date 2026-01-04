<?php

namespace App\Containers\Monitoring\NutritionalAssessment\Tasks;

use App\Containers\Monitoring\NutritionalAssessment\Data\Repositories\NutritionalAssessmentRepository;
use App\Containers\Monitoring\NutritionalAssessment\Events\NutritionalAssessmentCreated;
use App\Containers\Monitoring\NutritionalAssessment\Models\NutritionalAssessment;
use App\Ship\Parents\Tasks\Task as ParentTask;

final class CreateNutritionalAssessmentTask extends ParentTask
{
    public function __construct(
        private readonly NutritionalAssessmentRepository $repository,
    ) {
    }

    public function run(array $data): NutritionalAssessment
    {
        // Crear la valoración
        $nutritionalAssessment = $this->repository->create($data);

        // Cargar la relación child necesaria para calcular z-scores
        $nutritionalAssessment->load('child');

        // Calcular todos los z-scores automáticamente usando las tablas WHO
        $nutritionalAssessment->calculateAllZScores();
        
        // Guardar los z-scores y clasificaciones calculados
        $nutritionalAssessment->save();

        NutritionalAssessmentCreated::dispatch($nutritionalAssessment);

        return $nutritionalAssessment;
    }
}
