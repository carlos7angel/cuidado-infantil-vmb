<?php

namespace App\Containers\Monitoring\Attendance\Actions;

use App\Containers\Monitoring\Attendance\Models\Attendance;
use App\Containers\Monitoring\Attendance\Tasks\UpdateAttendanceTask;
use App\Containers\Monitoring\Attendance\UI\API\Requests\UpdateAttendanceRequest;
use App\Ship\Parents\Actions\Action as ParentAction;

final class UpdateAttendanceAction extends ParentAction
{
    public function __construct(
        private readonly UpdateAttendanceTask $updateAttendanceTask,
    ) {
    }

    public function run(UpdateAttendanceRequest $request): Attendance
    {
        $data = $request->sanitize([
            // add your request data here
        ]);

        return $this->updateAttendanceTask->run($data, $request->id);
    }
}
