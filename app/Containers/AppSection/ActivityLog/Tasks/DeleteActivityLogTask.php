<?php

namespace App\Containers\AppSection\ActivityLog\Tasks;

use App\Containers\AppSection\ActivityLog\Data\Repositories\ActivityLogRepository;
use App\Ship\Parents\Tasks\Task as ParentTask;

final class DeleteActivityLogTask extends ParentTask
{
    public function __construct(
        private readonly ActivityLogRepository $repository,
    ) {
    }

    public function run($id): bool
    {
        return $this->repository->delete($id);
    }
}
