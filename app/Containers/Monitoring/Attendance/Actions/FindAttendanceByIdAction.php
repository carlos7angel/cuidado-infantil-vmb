<?php

namespace App\Containers\Monitoring\Attendance\Actions;

use App\Containers\Monitoring\Attendance\Models\Attendance;
use App\Containers\Monitoring\Attendance\Tasks\FindAttendanceByIdTask;
use App\Containers\Monitoring\Attendance\UI\API\Requests\FindAttendanceByIdRequest;
use App\Ship\Parents\Actions\Action as ParentAction;

final class FindAttendanceByIdAction extends ParentAction
{
    public function __construct(
        private readonly FindAttendanceByIdTask $findAttendanceByIdTask,
    ) {
    }

    public function run(FindAttendanceByIdRequest $request): Attendance
    {
        return $this->findAttendanceByIdTask->run($request->id);
    }
}
