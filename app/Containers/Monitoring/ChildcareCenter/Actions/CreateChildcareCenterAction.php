<?php

namespace App\Containers\Monitoring\ChildcareCenter\Actions;

use App\Containers\Monitoring\ChildcareCenter\Models\ChildcareCenter;
use App\Containers\Monitoring\ChildcareCenter\Tasks\CreateChildcareCenterTask;
use App\Containers\Monitoring\ChildcareCenter\UI\API\Requests\CreateChildcareCenterRequest;
use App\Ship\Parents\Actions\Action as ParentAction;

final class CreateChildcareCenterAction extends ParentAction
{
    public function __construct(
        private readonly CreateChildcareCenterTask $createChildcareCenterTask,
    ) {
    }

    public function run(CreateChildcareCenterRequest $request): ChildcareCenter
    {
        $data = $request->sanitize([
            // add your request data here
        ]);

        return $this->createChildcareCenterTask->run($data);
    }
}
