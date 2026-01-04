<?php

namespace App\Containers\Monitoring\ChildEnrollment\Tasks;

use App\Containers\Monitoring\ChildEnrollment\Data\Repositories\ChildEnrollmentRepository;
use App\Containers\Monitoring\ChildEnrollment\Models\ChildEnrollment;
use App\Ship\Parents\Tasks\Task as ParentTask;

final class UpdateChildEnrollmentTask extends ParentTask
{
    public function __construct(
        private readonly ChildEnrollmentRepository $repository,
    ) {
    }

    public function run(array $data, $id): ChildEnrollment
    {
        return $this->repository->update($data, $id);
    }
}
