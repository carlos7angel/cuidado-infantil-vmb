<?php

namespace App\Containers\AppSection\ActivityLog\Actions;

use App\Containers\AppSection\ActivityLog\Tasks\ListActivityLogsTask;
use App\Containers\AppSection\ActivityLog\UI\API\Requests\ListActivityLogsRequest;
use App\Ship\Parents\Actions\Action as ParentAction;

final class ListActivityLogsAction extends ParentAction
{
    public function __construct(
        private readonly ListActivityLogsTask $listActivityLogsTask,
    ) {
    }

    public function run(ListActivityLogsRequest $request): mixed
    {
        return $this->listActivityLogsTask->run();
    }
}
