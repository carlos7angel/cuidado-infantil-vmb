<?php

namespace App\Containers\Monitoring\ChildDevelopment\Tasks;

use App\Containers\Monitoring\ChildDevelopment\Data\Repositories\ChildDevelopmentEvaluationRepository;
use App\Containers\Monitoring\ChildDevelopment\Models\ChildDevelopmentEvaluation;
use App\Ship\Parents\Tasks\Task as ParentTask;

final class UpdateChildDevelopmentTask extends ParentTask
{
    public function __construct(
        private readonly ChildDevelopmentEvaluationRepository $repository,
    ) {
    }

    public function run(array $data, $id): ChildDevelopmentEvaluation
    {
        return $this->repository->update($data, $id);
    }
}
