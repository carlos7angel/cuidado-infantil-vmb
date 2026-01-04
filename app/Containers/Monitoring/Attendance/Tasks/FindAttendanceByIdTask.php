<?php

namespace App\Containers\Monitoring\Attendance\Tasks;

use App\Containers\Monitoring\Attendance\Data\Repositories\AttendanceRepository;
use App\Containers\Monitoring\Attendance\Models\Attendance;
use App\Ship\Parents\Tasks\Task as ParentTask;

final class FindAttendanceByIdTask extends ParentTask
{
    public function __construct(
        private readonly AttendanceRepository $repository,
    ) {
    }

    public function run($id): Attendance
    {
        return $this->repository->findOrFail($id);
    }
}
