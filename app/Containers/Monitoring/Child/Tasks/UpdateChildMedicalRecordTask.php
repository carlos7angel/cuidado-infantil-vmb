<?php

namespace App\Containers\Monitoring\Child\Tasks;

use App\Containers\Monitoring\Child\Data\Repositories\ChildMedicalRecordRepository;
use App\Containers\Monitoring\Child\Models\ChildMedicalRecord;
use App\Ship\Parents\Tasks\Task as ParentTask;

final class UpdateChildMedicalRecordTask extends ParentTask
{
    public function __construct(
        private readonly ChildMedicalRecordRepository $repository,
    ) {
    }

    public function run(array $data, int $childId): ChildMedicalRecord
    {
        $medicalRecord = $this->repository->findByField('child_id', $childId)->first();
        
        if ($medicalRecord) {
            return $this->repository->update($data, $medicalRecord->id);
        }
        
        // Si no existe, crear uno nuevo
        $data['child_id'] = $childId;
        return $this->repository->create($data);
    }
}

