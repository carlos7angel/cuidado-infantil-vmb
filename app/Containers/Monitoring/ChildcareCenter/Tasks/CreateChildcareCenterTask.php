<?php

namespace App\Containers\Monitoring\ChildcareCenter\Tasks;

use App\Containers\Monitoring\ChildcareCenter\Data\Repositories\ChildcareCenterRepository;
use App\Containers\Monitoring\ChildcareCenter\Events\ChildcareCenterCreated;
use App\Containers\Monitoring\ChildcareCenter\Models\ChildcareCenter;
use App\Ship\Parents\Tasks\Task as ParentTask;

final class CreateChildcareCenterTask extends ParentTask
{
    public function __construct(
        private readonly ChildcareCenterRepository $repository,
    ) {
    }

    public function run(array $data): ChildcareCenter
    {
        $childcareCenter = $this->repository->create($data);

        ChildcareCenterCreated::dispatch($childcareCenter);

        return $childcareCenter;
    }
}
