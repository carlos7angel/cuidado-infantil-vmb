<?php

namespace App\Containers\Monitoring\ChildEnrollment\Tasks;

use App\Containers\Monitoring\ChildEnrollment\Data\Repositories\ChildEnrollmentRepository;
use App\Ship\Parents\Tasks\Task as ParentTask;

final class ListChildEnrollmentsTask extends ParentTask
{
    public function __construct(
        private readonly ChildEnrollmentRepository $repository,
    ) {
    }

    public function run(): mixed
    {
        return $this->repository->addRequestCriteria()->paginate();
    }
}
