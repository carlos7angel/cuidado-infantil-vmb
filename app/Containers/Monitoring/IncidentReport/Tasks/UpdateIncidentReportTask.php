<?php

namespace App\Containers\Monitoring\IncidentReport\Tasks;

use App\Containers\Monitoring\IncidentReport\Data\Repositories\IncidentReportRepository;
use App\Containers\Monitoring\IncidentReport\Models\IncidentReport;
use App\Ship\Parents\Tasks\Task as ParentTask;

final class UpdateIncidentReportTask extends ParentTask
{
    public function __construct(
        private readonly IncidentReportRepository $repository,
    ) {
    }

    public function run(array $data, $id): IncidentReport
    {
        return $this->repository->update($data, $id);
    }
}
