<?php

namespace App\Containers\Monitoring\ChildDevelopment\Tasks;

use App\Containers\Monitoring\ChildDevelopment\Data\Repositories\ChildDevelopmentEvaluationRepository;
use App\Ship\Parents\Tasks\Task as ParentTask;

final class ListChildDevelopmentsTask extends ParentTask
{
    public function __construct(
        private readonly ChildDevelopmentEvaluationRepository $repository,
    ) {
    }

    public function run(): mixed
    {
        return $this->repository->addRequestCriteria()->paginate();
    }
}
