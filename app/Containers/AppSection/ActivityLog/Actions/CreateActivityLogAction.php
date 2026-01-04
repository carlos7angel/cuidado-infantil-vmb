<?php

namespace App\Containers\AppSection\ActivityLog\Actions;

use App\Containers\AppSection\ActivityLog\Models\ActivityLog;
use App\Containers\AppSection\ActivityLog\Tasks\CreateActivityLogTask;
use App\Containers\AppSection\ActivityLog\UI\API\Requests\CreateActivityLogRequest;
use App\Ship\Parents\Actions\Action as ParentAction;

final class CreateActivityLogAction extends ParentAction
{
    public function __construct(
        private readonly CreateActivityLogTask $createActivityLogTask,
    ) {
    }

    public function run(CreateActivityLogRequest $request): ActivityLog
    {
        $data = $request->sanitize([
            // add your request data here
        ]);

        return $this->createActivityLogTask->run($data);
    }
}
