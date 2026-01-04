<?php

namespace App\Containers\Monitoring\ChildEnrollment\Actions;

use App\Containers\Monitoring\ChildEnrollment\Models\ChildEnrollment;
use App\Containers\Monitoring\ChildEnrollment\Tasks\FindChildEnrollmentByIdTask;
use App\Containers\Monitoring\ChildEnrollment\UI\API\Requests\FindChildEnrollmentByIdRequest;
use App\Ship\Parents\Actions\Action as ParentAction;

final class FindChildEnrollmentByIdAction extends ParentAction
{
    public function __construct(
        private readonly FindChildEnrollmentByIdTask $findChildEnrollmentByIdTask,
    ) {
    }

    public function run(FindChildEnrollmentByIdRequest $request): ChildEnrollment
    {
        return $this->findChildEnrollmentByIdTask->run($request->id);
    }
}
