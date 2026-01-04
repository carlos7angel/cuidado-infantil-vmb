<?php

namespace App\Containers\Monitoring\ChildcareCenter\Tasks;

use App\Containers\Monitoring\ChildcareCenter\Data\Repositories\ChildcareCenterRepository;
use App\Containers\Monitoring\ChildcareCenter\Events\ChildcareCenterRequested;
use App\Containers\Monitoring\ChildcareCenter\Models\ChildcareCenter;
use App\Containers\Monitoring\ChildcareCenter\UI\API\Requests\FindChildcareCenterByIdRequest;
use App\Ship\Parents\Tasks\Task as ParentTask;

final class FindChildcareCenterByIdTask extends ParentTask
{
    public function __construct(
        private readonly ChildcareCenterRepository $repository,
    ) {
    }

    public function run($id): ChildcareCenter
    {
        $childcareCenter = $this->repository->findOrFail($id);

        ChildcareCenterRequested::dispatch($childcareCenter);

        return $childcareCenter;
    }
}
