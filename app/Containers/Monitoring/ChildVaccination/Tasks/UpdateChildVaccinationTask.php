<?php

namespace App\Containers\Monitoring\ChildVaccination\Tasks;

use App\Containers\Monitoring\ChildVaccination\Data\Repositories\ChildVaccinationRepository;
use App\Containers\Monitoring\ChildVaccination\Models\ChildVaccination;
use App\Ship\Parents\Tasks\Task as ParentTask;

final class UpdateChildVaccinationTask extends ParentTask
{
    public function __construct(
        private readonly ChildVaccinationRepository $repository,
    ) {
    }

    public function run(array $data, $id): ChildVaccination
    {
        return $this->repository->update($data, $id);
    }
}
