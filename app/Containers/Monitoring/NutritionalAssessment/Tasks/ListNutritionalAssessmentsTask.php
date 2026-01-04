<?php

namespace App\Containers\Monitoring\NutritionalAssessment\Tasks;

use App\Containers\Monitoring\NutritionalAssessment\Data\Repositories\NutritionalAssessmentRepository;
use App\Containers\Monitoring\NutritionalAssessment\Events\NutritionalAssessmentsListed;
use App\Ship\Parents\Tasks\Task as ParentTask;

final class ListNutritionalAssessmentsTask extends ParentTask
{
    public function __construct(
        private readonly NutritionalAssessmentRepository $repository,
    ) {
    }

    public function run(): mixed
    {
        $result = $this->repository->addRequestCriteria()->paginate();

        NutritionalAssessmentsListed::dispatch($result);

        return $result;
    }
}
