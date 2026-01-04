<?php

namespace App\Containers\AppSection\ActivityLog\Actions;

use App\Containers\AppSection\ActivityLog\Tasks\DeleteActivityLogTask;
use App\Containers\AppSection\ActivityLog\UI\API\Requests\DeleteActivityLogRequest;
use App\Ship\Parents\Actions\Action as ParentAction;

final class DeleteActivityLogAction extends ParentAction
{
    public function __construct(
        private readonly DeleteActivityLogTask $deleteActivityLogTask,
    ) {
    }

    public function run(DeleteActivityLogRequest $request): bool
    {
        return $this->deleteActivityLogTask->run($request->id);
    }
}
