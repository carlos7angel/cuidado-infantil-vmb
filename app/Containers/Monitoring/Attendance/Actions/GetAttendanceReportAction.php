<?php

namespace App\Containers\Monitoring\Attendance\Actions;

use App\Containers\Monitoring\Attendance\Tasks\GetAttendanceReportTask;
use App\Ship\Parents\Actions\Action as ParentAction;

final class GetAttendanceReportAction extends ParentAction
{
    public function __construct(
        private readonly GetAttendanceReportTask $getAttendanceReportTask,
    ) {
    }

    public function run(object $request): array
    {
        return $this->getAttendanceReportTask->run($request);
    }
}

