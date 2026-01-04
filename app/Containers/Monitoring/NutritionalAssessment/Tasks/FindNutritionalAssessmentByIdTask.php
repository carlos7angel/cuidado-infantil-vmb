<?php

namespace App\Containers\Monitoring\NutritionalAssessment\Tasks;

use App\Containers\Monitoring\NutritionalAssessment\Data\Repositories\NutritionalAssessmentRepository;
use App\Containers\Monitoring\NutritionalAssessment\Events\NutritionalAssessmentRequested;
use App\Containers\Monitoring\NutritionalAssessment\Models\NutritionalAssessment;
use App\Ship\Parents\Tasks\Task as ParentTask;

final class FindNutritionalAssessmentByIdTask extends ParentTask
{
    public function __construct(
        private readonly NutritionalAssessmentRepository $repository,
    ) {
    }

    public function run($id): NutritionalAssessment
    {
        $nutritionalAssessment = $this->repository->findOrFail($id);

        // Cargar la relación child necesaria para el transformer
        $nutritionalAssessment->load('child');

        NutritionalAssessmentRequested::dispatch($nutritionalAssessment);

        return $nutritionalAssessment;
    }
}
