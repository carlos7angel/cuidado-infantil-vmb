<?php

namespace App\Containers\Monitoring\ChildVaccination\Tasks;

use App\Containers\Monitoring\ChildVaccination\Data\Repositories\ChildVaccinationRepository;
use App\Containers\Monitoring\ChildVaccination\Models\ChildVaccination;
use App\Ship\Parents\Tasks\Task as ParentTask;

final class FindChildVaccinationByIdTask extends ParentTask
{
    public function __construct(
        private readonly ChildVaccinationRepository $repository,
    ) {
    }

    public function run($id): ChildVaccination
    {
        return $this->repository->findOrFail($id);
    }
}
