<?php

namespace App\Containers\Monitoring\ChildcareCenter\Tasks;

use App\Containers\Monitoring\ChildcareCenter\Data\Repositories\ChildcareCenterRepository;
use App\Containers\Monitoring\ChildcareCenter\Events\ChildcareCenterDeleted;
use App\Ship\Parents\Tasks\Task as ParentTask;

final class DeleteChildcareCenterTask extends ParentTask
{
    public function __construct(
        private readonly ChildcareCenterRepository $repository,
    ) {
    }

    public function run($id): bool
    {
        $result = $this->repository->delete($id);

        ChildcareCenterDeleted::dispatch($result);

        return $result;
    }
}
