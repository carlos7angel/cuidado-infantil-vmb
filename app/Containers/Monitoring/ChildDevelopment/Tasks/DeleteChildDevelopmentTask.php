<?php

namespace App\Containers\Monitoring\ChildDevelopment\Tasks;

use App\Containers\Monitoring\ChildDevelopment\Data\Repositories\ChildDevelopmentEvaluationRepository;
use App\Ship\Parents\Tasks\Task as ParentTask;

final class DeleteChildDevelopmentTask extends ParentTask
{
    public function __construct(
        private readonly ChildDevelopmentEvaluationRepository $repository,
    ) {
    }

    public function run($id): bool
    {
        return $this->repository->delete($id);
    }
}
