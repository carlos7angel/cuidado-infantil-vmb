<?php

namespace App\Containers\Monitoring\ChildcareCenter\Actions;

use App\Containers\Monitoring\ChildcareCenter\Tasks\DeleteChildcareCenterTask;
use App\Containers\Monitoring\ChildcareCenter\UI\API\Requests\DeleteChildcareCenterRequest;
use App\Ship\Parents\Actions\Action as ParentAction;

final class DeleteChildcareCenterAction extends ParentAction
{
    public function __construct(
        private readonly DeleteChildcareCenterTask $deleteChildcareCenterTask,
    ) {
    }

    public function run(DeleteChildcareCenterRequest $request): bool
    {
        return $this->deleteChildcareCenterTask->run($request->id);
    }
}
