<?php

namespace App\Containers\Monitoring\Attendance\Actions;

use App\Containers\Monitoring\Attendance\Tasks\ListAttendancesTask;
use App\Containers\Monitoring\Attendance\UI\API\Requests\ListAttendancesRequest;
use App\Ship\Parents\Actions\Action as ParentAction;

final class ListAttendancesAction extends ParentAction
{
    public function __construct(
        private readonly ListAttendancesTask $listAttendancesTask,
    ) {
    }

    public function run(ListAttendancesRequest $request): mixed
    {
        return $this->listAttendancesTask->run();
    }
}
