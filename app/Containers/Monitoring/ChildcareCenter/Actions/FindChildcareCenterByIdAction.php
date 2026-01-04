<?php

namespace App\Containers\Monitoring\ChildcareCenter\Actions;

use App\Containers\Monitoring\ChildcareCenter\Models\ChildcareCenter;
use App\Containers\Monitoring\ChildcareCenter\Tasks\FindChildcareCenterByIdTask;
use App\Containers\Monitoring\ChildcareCenter\UI\API\Requests\FindChildcareCenterByIdRequest;
use App\Ship\Parents\Actions\Action as ParentAction;

final class FindChildcareCenterByIdAction extends ParentAction
{
    public function __construct(
        private readonly FindChildcareCenterByIdTask $findChildcareCenterByIdTask,
    ) {
    }

    public function run(FindChildcareCenterByIdRequest $request): ChildcareCenter
    {
        return $this->findChildcareCenterByIdTask->run($request->id);
    }
}
