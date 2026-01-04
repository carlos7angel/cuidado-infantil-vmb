<?php

namespace App\Containers\Monitoring\Educator\Tasks;

use App\Containers\Monitoring\Educator\Data\Repositories\EducatorRepository;
use App\Containers\Monitoring\Educator\Events\EducatorsListed;
use App\Ship\Parents\Tasks\Task as ParentTask;

final class ListEducatorsTask extends ParentTask
{
    public function __construct(
        private readonly EducatorRepository $repository,
    ) {
    }

    public function run(): mixed
    {
        $result = $this->repository->addRequestCriteria()->paginate();

        EducatorsListed::dispatch($result);

        return $result;
    }
}
