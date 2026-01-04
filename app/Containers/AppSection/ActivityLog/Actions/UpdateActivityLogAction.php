<?php

namespace App\Containers\AppSection\ActivityLog\Actions;

use App\Containers\AppSection\ActivityLog\Models\ActivityLog;
use App\Containers\AppSection\ActivityLog\Tasks\UpdateActivityLogTask;
use App\Containers\AppSection\ActivityLog\UI\API\Requests\UpdateActivityLogRequest;
use App\Ship\Parents\Actions\Action as ParentAction;

final class UpdateActivityLogAction extends ParentAction
{
    public function __construct(
        private readonly UpdateActivityLogTask $updateActivityLogTask,
    ) {
    }

    public function run(UpdateActivityLogRequest $request): ActivityLog
    {
        $data = $request->sanitize([
            // add your request data here
        ]);

        return $this->updateActivityLogTask->run($data, $request->id);
    }
}
