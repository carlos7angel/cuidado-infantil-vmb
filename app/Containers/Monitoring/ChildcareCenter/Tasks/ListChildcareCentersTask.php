<?php

namespace App\Containers\Monitoring\ChildcareCenter\Tasks;

use App\Containers\Monitoring\ChildcareCenter\Data\Repositories\ChildcareCenterRepository;
use App\Containers\Monitoring\ChildcareCenter\Events\ChildcareCentersListed;
use App\Ship\Parents\Tasks\Task as ParentTask;

final class ListChildcareCentersTask extends ParentTask
{
    public function __construct(
        private readonly ChildcareCenterRepository $repository,
    ) {
    }

    public function run(): mixed
    {
        $result = $this->repository->addRequestCriteria()->paginate();

        ChildcareCentersListed::dispatch($result);

        return $result;
    }
}
