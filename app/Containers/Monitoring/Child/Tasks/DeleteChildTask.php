<?php

namespace App\Containers\Monitoring\Child\Tasks;

use App\Containers\Monitoring\Child\Data\Repositories\ChildRepository;
use App\Containers\Monitoring\Child\Events\ChildDeleted;
use App\Ship\Parents\Tasks\Task as ParentTask;

final class DeleteChildTask extends ParentTask
{
    public function __construct(
        private readonly ChildRepository $repository,
    ) {
    }

    public function run($id): bool
    {
        $result = $this->repository->delete($id);

        ChildDeleted::dispatch($result);

        return $result;
    }
}
