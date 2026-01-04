<?php

namespace App\Containers\Monitoring\Attendance\Actions;

use App\Containers\Monitoring\Attendance\Tasks\DeleteAttendanceTask;
use App\Containers\Monitoring\Attendance\UI\API\Requests\DeleteAttendanceRequest;
use App\Ship\Parents\Actions\Action as ParentAction;

final class DeleteAttendanceAction extends ParentAction
{
    public function __construct(
        private readonly DeleteAttendanceTask $deleteAttendanceTask,
    ) {
    }

    public function run(DeleteAttendanceRequest $request): bool
    {
        return $this->deleteAttendanceTask->run($request->id);
    }
}
