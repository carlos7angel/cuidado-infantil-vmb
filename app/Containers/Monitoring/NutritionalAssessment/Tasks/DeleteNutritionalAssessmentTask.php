<?php

namespace App\Containers\Monitoring\NutritionalAssessment\Tasks;

use App\Containers\Monitoring\NutritionalAssessment\Data\Repositories\NutritionalAssessmentRepository;
use App\Containers\Monitoring\NutritionalAssessment\Events\NutritionalAssessmentDeleted;
use App\Ship\Parents\Tasks\Task as ParentTask;

final class DeleteNutritionalAssessmentTask extends ParentTask
{
    public function __construct(
        private readonly NutritionalAssessmentRepository $repository,
    ) {
    }

    public function run($id): bool
    {
        $result = $this->repository->delete($id);

        NutritionalAssessmentDeleted::dispatch($result);

        return $result;
    }
}
