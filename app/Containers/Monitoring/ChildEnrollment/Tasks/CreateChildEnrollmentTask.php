<?php

namespace App\Containers\Monitoring\ChildEnrollment\Tasks;

use App\Containers\Monitoring\ChildEnrollment\Data\Repositories\ChildEnrollmentRepository;
use App\Containers\Monitoring\ChildEnrollment\Models\ChildEnrollment;
use App\Ship\Parents\Tasks\Task as ParentTask;

final class CreateChildEnrollmentTask extends ParentTask
{
    public function __construct(
        private readonly ChildEnrollmentRepository $repository,
    ) {
    }

    public function run(array $data): ChildEnrollment
    {
        return $this->repository->create($data);
    }
}
