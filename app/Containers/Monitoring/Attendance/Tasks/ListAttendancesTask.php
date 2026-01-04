<?php

namespace App\Containers\Monitoring\Attendance\Tasks;

use App\Containers\Monitoring\Attendance\Data\Repositories\AttendanceRepository;
use App\Ship\Parents\Tasks\Task as ParentTask;

final class ListAttendancesTask extends ParentTask
{
    public function __construct(
        private readonly AttendanceRepository $repository,
    ) {
    }

    public function run(): mixed
    {
        return $this->repository->addRequestCriteria()->paginate();
    }
}
