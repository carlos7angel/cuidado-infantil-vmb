<?php

namespace App\Containers\Monitoring\Child\Tasks;

use App\Containers\Monitoring\Child\Data\Repositories\ChildSocialRecordRepository;
use App\Containers\Monitoring\Child\Models\ChildSocialRecord;
use App\Ship\Parents\Tasks\Task as ParentTask;

final class CreateChildSocialRecordTask extends ParentTask
{
    public function __construct(
        private readonly ChildSocialRecordRepository $repository,
    ) {
    }

    public function run(array $data): ChildSocialRecord
    {
        return $this->repository->create($data);
    }
}