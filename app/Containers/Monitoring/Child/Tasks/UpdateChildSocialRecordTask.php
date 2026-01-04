<?php

namespace App\Containers\Monitoring\Child\Tasks;

use App\Containers\Monitoring\Child\Data\Repositories\ChildSocialRecordRepository;
use App\Containers\Monitoring\Child\Models\ChildSocialRecord;
use App\Ship\Parents\Tasks\Task as ParentTask;

final class UpdateChildSocialRecordTask extends ParentTask
{
    public function __construct(
        private readonly ChildSocialRecordRepository $repository,
    ) {
    }

    public function run(array $data, int $childId): ChildSocialRecord
    {
        $socialRecord = $this->repository->findByField('child_id', $childId)->first();
        
        if ($socialRecord) {
            return $this->repository->update($data, $socialRecord->id);
        }
        
        // Si no existe, crear uno nuevo
        $data['child_id'] = $childId;
        return $this->repository->create($data);
    }
}

