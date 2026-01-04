<?php

namespace App\Containers\Monitoring\ChildDevelopment\Actions;

use App\Containers\Monitoring\ChildDevelopment\Models\ChildDevelopment;
use App\Containers\Monitoring\ChildDevelopment\Tasks\UpdateChildDevelopmentTask;
use App\Containers\Monitoring\ChildDevelopment\UI\API\Requests\UpdateChildDevelopmentRequest;
use App\Ship\Parents\Actions\Action as ParentAction;

final class UpdateChildDevelopmentAction extends ParentAction
{
    public function __construct(
        private readonly UpdateChildDevelopmentTask $updateChildDevelopmentTask,
    ) {
    }

    public function run(UpdateChildDevelopmentRequest $request): ChildDevelopment
    {
        $data = $request->sanitize([
            // add your request data here
        ]);

        return $this->updateChildDevelopmentTask->run($data, $request->id);
    }
}
