<?php

namespace App\Containers\Monitoring\ChildEnrollment\Actions;

use App\Containers\Monitoring\ChildEnrollment\Models\ChildEnrollment;
use App\Containers\Monitoring\ChildEnrollment\Tasks\CreateChildEnrollmentTask;
use App\Containers\Monitoring\ChildEnrollment\UI\API\Requests\CreateChildEnrollmentRequest;
use App\Ship\Parents\Actions\Action as ParentAction;

final class CreateChildEnrollmentAction extends ParentAction
{
    public function __construct(
        private readonly CreateChildEnrollmentTask $createChildEnrollmentTask,
    ) {
    }

    public function run(CreateChildEnrollmentRequest $request): ChildEnrollment
    {
        $data = $request->sanitize([
            // add your request data here
        ]);

        return $this->createChildEnrollmentTask->run($data);
    }
}
