<?php

namespace App\Containers\Monitoring\IncidentReport\Tasks;

use App\Containers\Monitoring\IncidentReport\Data\Repositories\IncidentReportRepository;
use App\Ship\Parents\Tasks\Task as ParentTask;

final class DeleteIncidentReportTask extends ParentTask
{
    public function __construct(
        private readonly IncidentReportRepository $repository,
    ) {
    }

    public function run($id): bool
    {
        return $this->repository->delete($id);
    }
}
