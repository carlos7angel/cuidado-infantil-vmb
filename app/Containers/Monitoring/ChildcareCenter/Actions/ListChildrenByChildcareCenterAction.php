<?php

namespace App\Containers\Monitoring\ChildcareCenter\Actions;

use App\Containers\Monitoring\ChildcareCenter\Tasks\FindChildcareCenterByIdTask;
use App\Containers\Monitoring\ChildcareCenter\Tasks\ListActiveChildrenByChildcareCenterTask;
use App\Containers\Monitoring\ChildcareCenter\UI\API\Requests\ListChildrenByChildcareCenterRequest;
use App\Ship\Parents\Actions\Action as ParentAction;
use Illuminate\Database\Eloquent\Collection;

final class ListChildrenByChildcareCenterAction extends ParentAction
{
    public function __construct(
        private readonly FindChildcareCenterByIdTask $findChildcareCenterByIdTask,
        private readonly ListActiveChildrenByChildcareCenterTask $listActiveChildrenByChildcareCenterTask,
    ) {
    }

    public function run(ListChildrenByChildcareCenterRequest $request): Collection
    {
        $childcareCenter = $this->findChildcareCenterByIdTask->run($request->id);

        return $this->listActiveChildrenByChildcareCenterTask->run($childcareCenter);
    }
}

