<?php

namespace App\Containers\Monitoring\Attendance\Actions;

use App\Containers\Monitoring\Attendance\Tasks\ListChildrenAttendanceByDateRangeTask;
use App\Containers\Monitoring\Attendance\UI\API\Requests\ListChildrenAttendanceByDateRangeRequest;
use App\Containers\Monitoring\ChildcareCenter\Tasks\FindChildcareCenterByIdTask;
use App\Ship\Parents\Actions\Action as ParentAction;

final class ListChildrenAttendanceByDateRangeAction extends ParentAction
{
    public function __construct(
        private readonly FindChildcareCenterByIdTask $findChildcareCenterByIdTask,
        private readonly ListChildrenAttendanceByDateRangeTask $listChildrenAttendanceByDateRangeTask,
    ) {
    }

    public function run(ListChildrenAttendanceByDateRangeRequest $request): array
    {
        $childcareCenter = $this->findChildcareCenterByIdTask->run($request->childcare_center_id);

        return $this->listChildrenAttendanceByDateRangeTask->run(
            $childcareCenter,
            $request->getStartDate(),
            $request->getEndDate()
        );
    }
}

