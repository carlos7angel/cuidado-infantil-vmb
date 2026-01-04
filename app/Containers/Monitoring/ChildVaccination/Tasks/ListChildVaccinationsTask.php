<?php

namespace App\Containers\Monitoring\ChildVaccination\Tasks;

use App\Containers\Monitoring\ChildVaccination\Data\Repositories\ChildVaccinationRepository;
use App\Ship\Parents\Tasks\Task as ParentTask;

final class ListChildVaccinationsTask extends ParentTask
{
    public function __construct(
        private readonly ChildVaccinationRepository $repository,
    ) {
    }

    public function run(): mixed
    {
        return $this->repository->addRequestCriteria()->paginate();
    }
}
