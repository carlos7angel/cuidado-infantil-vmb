<?php

namespace App\Containers\Monitoring\Educator\Tasks;

use App\Containers\Monitoring\Educator\Data\Repositories\EducatorRepository;
use App\Containers\Monitoring\Educator\Events\EducatorDeleted;
use App\Ship\Parents\Tasks\Task as ParentTask;

final class DeleteEducatorTask extends ParentTask
{
    public function __construct(
        private readonly EducatorRepository $repository,
    ) {
    }

    public function run($id): bool
    {
        $result = $this->repository->delete($id);

        EducatorDeleted::dispatch($result);

        return $result;
    }
}
