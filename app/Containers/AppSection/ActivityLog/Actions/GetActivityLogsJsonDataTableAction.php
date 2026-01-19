<?php

namespace App\Containers\AppSection\ActivityLog\Actions;

use App\Containers\AppSection\ActivityLog\Tasks\GetActivityLogsJsonDataTableTask;
use App\Containers\Frontend\Administrator\UI\WEB\Requests\ActivityLog\GetActivityLogsJsonDataTableRequest;
use App\Ship\Parents\Actions\Action as ParentAction;

final class GetActivityLogsJsonDataTableAction extends ParentAction
{
    public function __construct(
        private readonly GetActivityLogsJsonDataTableTask $getActivityLogsJsonDataTableTask,
    ) {
    }

    public function run(GetActivityLogsJsonDataTableRequest $request): mixed
    {
        return $this->getActivityLogsJsonDataTableTask->run($request);
    }
}

