<?php

namespace App\Containers\Monitoring\ChildcareCenter\Tasks;

use App\Containers\Monitoring\ChildcareCenter\Data\Repositories\ChildcareCenterRepository;
use App\Containers\Monitoring\ChildcareCenter\Events\ChildcareCenterUpdated;
use App\Containers\Monitoring\ChildcareCenter\Models\ChildcareCenter;
use App\Ship\Parents\Tasks\Task as ParentTask;

final class UpdateChildcareCenterTask extends ParentTask
{
    public function __construct(
        private readonly ChildcareCenterRepository $repository,
    ) {
    }

    public function run(array $data, $id): ChildcareCenter
    {
        $childcareCenter = $this->repository->update($data, $id);

        ChildcareCenterUpdated::dispatch($childcareCenter);

        return $childcareCenter;
    }
}
