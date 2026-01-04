<?php

namespace App\Containers\Monitoring\NutritionalAssessment\Tasks;

use App\Containers\Monitoring\NutritionalAssessment\Data\Repositories\NutritionalAssessmentRepository;
use App\Containers\Monitoring\NutritionalAssessment\Events\NutritionalAssessmentUpdated;
use App\Containers\Monitoring\NutritionalAssessment\Models\NutritionalAssessment;
use App\Ship\Parents\Tasks\Task as ParentTask;

final class UpdateNutritionalAssessmentTask extends ParentTask
{
    public function __construct(
        private readonly NutritionalAssessmentRepository $repository,
    ) {
    }

    public function run(array $data, $id): NutritionalAssessment
    {
        $nutritionalAssessment = $this->repository->update($data, $id);

        NutritionalAssessmentUpdated::dispatch($nutritionalAssessment);

        return $nutritionalAssessment;
    }
}
