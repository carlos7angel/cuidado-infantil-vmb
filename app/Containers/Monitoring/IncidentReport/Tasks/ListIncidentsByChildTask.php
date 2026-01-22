<?php

namespace App\Containers\Monitoring\IncidentReport\Tasks;

use App\Containers\Monitoring\IncidentReport\Data\Repositories\IncidentReportRepository;
use App\Ship\Parents\Tasks\Task as ParentTask;

final class ListIncidentsByChildTask extends ParentTask
{
    public function __construct(
        private readonly IncidentReportRepository $repository,
    ) {
    }

    public function run(int $childId): mixed
    {
        return $this->repository
            ->scopeQuery(function ($query) use ($childId) {
                return $query
                    ->where('child_id', $childId)
                    ->orderByDesc('incident_date')
                    ->orderByDesc('created_at');
            })
            ->addRequestCriteria()
            ->paginate();
    }
}
