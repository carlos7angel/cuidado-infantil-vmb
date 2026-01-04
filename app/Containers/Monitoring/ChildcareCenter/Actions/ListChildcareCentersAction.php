<?php

namespace App\Containers\Monitoring\ChildcareCenter\Actions;

use App\Containers\Monitoring\ChildcareCenter\Tasks\ListChildcareCentersTask;
use App\Containers\Monitoring\ChildcareCenter\UI\API\Requests\ListChildcareCentersRequest;
use App\Ship\Parents\Actions\Action as ParentAction;

final class ListChildcareCentersAction extends ParentAction
{
    public function __construct(
        private readonly ListChildcareCentersTask $listChildcareCentersTask,
    ) {
    }

    public function run(ListChildcareCentersRequest $request): mixed
    {
        return $this->listChildcareCentersTask->run();
    }
}
