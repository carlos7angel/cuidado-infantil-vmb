<?php

namespace App\Containers\Monitoring\ChildcareCenter\Actions;

use App\Containers\Monitoring\ChildcareCenter\Models\ChildcareCenter;
use App\Containers\Monitoring\ChildcareCenter\Tasks\UpdateChildcareCenterTask;
use App\Containers\Monitoring\ChildcareCenter\UI\API\Requests\UpdateChildcareCenterRequest;
use App\Ship\Parents\Actions\Action as ParentAction;

final class UpdateChildcareCenterAction extends ParentAction
{
    public function __construct(
        private readonly UpdateChildcareCenterTask $updateChildcareCenterTask,
    ) {
    }

    public function run(UpdateChildcareCenterRequest $request): ChildcareCenter
    {
        $data = $request->sanitize([
            // add your request data here
        ]);

        return $this->updateChildcareCenterTask->run($data, $request->id);
    }
}
