<?php

namespace App\Containers\Monitoring\Educator\Tasks;

use App\Containers\Monitoring\Educator\Data\Repositories\EducatorRepository;
use App\Containers\Monitoring\Educator\Events\EducatorRequested;
use App\Containers\Monitoring\Educator\Models\Educator;
use App\Ship\Parents\Tasks\Task as ParentTask;

final class FindEducatorByIdTask extends ParentTask
{
    public function __construct(
        private readonly EducatorRepository $repository,
    ) {
    }

    public function run($id): Educator
    {
        $educator = $this->repository->findOrFail($id);

        EducatorRequested::dispatch($educator);

        return $educator;
    }
}
