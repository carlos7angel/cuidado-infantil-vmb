<?php

namespace App\Containers\Monitoring\Child\Tasks;

use App\Containers\Monitoring\Child\Data\Repositories\ChildMedicalRecordRepository;
use App\Containers\Monitoring\Child\Models\ChildMedicalRecord;
use App\Ship\Parents\Tasks\Task as ParentTask;

final class CreateChildMedicalRecordTask extends ParentTask
{
    public function __construct(
        private readonly ChildMedicalRecordRepository $repository,
    ) {
    }

    public function run(array $data): ChildMedicalRecord
    {
        return $this->repository->create($data);
    }
}