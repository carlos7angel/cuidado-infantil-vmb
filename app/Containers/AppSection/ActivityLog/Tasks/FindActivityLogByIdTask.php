<?php

namespace App\Containers\AppSection\ActivityLog\Tasks;

use App\Containers\AppSection\ActivityLog\Data\Repositories\ActivityLogRepository;
use App\Containers\AppSection\ActivityLog\Models\ActivityLog;
use App\Ship\Parents\Tasks\Task as ParentTask;

final class FindActivityLogByIdTask extends ParentTask
{
    public function __construct(
        private readonly ActivityLogRepository $repository,
    ) {
    }

    public function run($id): ActivityLog
    {
        return $this->repository->findOrFail($id);
    }
}
