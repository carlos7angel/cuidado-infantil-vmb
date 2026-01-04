<?php

namespace App\Containers\Monitoring\ChildEnrollment\Actions;

use App\Containers\Monitoring\ChildEnrollment\Tasks\DeleteChildEnrollmentTask;
use App\Containers\Monitoring\ChildEnrollment\UI\API\Requests\DeleteChildEnrollmentRequest;
use App\Ship\Parents\Actions\Action as ParentAction;

final class DeleteChildEnrollmentAction extends ParentAction
{
    public function __construct(
        private readonly DeleteChildEnrollmentTask $deleteChildEnrollmentTask,
    ) {
    }

    public function run(DeleteChildEnrollmentRequest $request): bool
    {
        return $this->deleteChildEnrollmentTask->run($request->id);
    }
}
