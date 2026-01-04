<?php

namespace App\Containers\Monitoring\ChildEnrollment\Actions;

use App\Containers\Monitoring\ChildEnrollment\Models\ChildEnrollment;
use App\Containers\Monitoring\ChildEnrollment\Tasks\UpdateChildEnrollmentTask;
use App\Containers\Monitoring\ChildEnrollment\UI\API\Requests\UpdateChildEnrollmentRequest;
use App\Ship\Parents\Actions\Action as ParentAction;

final class UpdateChildEnrollmentAction extends ParentAction
{
    public function __construct(
        private readonly UpdateChildEnrollmentTask $updateChildEnrollmentTask,
    ) {
    }

    public function run(UpdateChildEnrollmentRequest $request): ChildEnrollment
    {
        $data = $request->sanitize([
            // add your request data here
        ]);

        return $this->updateChildEnrollmentTask->run($data, $request->id);
    }
}
