<?php

namespace App\Containers\Monitoring\IncidentReport\Tasks;

use App\Containers\Monitoring\IncidentReport\Data\Repositories\IncidentReportRepository;
use App\Containers\Monitoring\IncidentReport\Models\IncidentReport;
use App\Ship\Parents\Tasks\Task as ParentTask;

final class FindIncidentReportByIdTask extends ParentTask
{
    public function __construct(
        private readonly IncidentReportRepository $repository,
    ) {
    }

    public function run($id): IncidentReport
    {
        $incidentReport = $this->repository->findOrFail($id);
        
        // Cargar relaciones necesarias
        $incidentReport->load([
            'child',
            'reportedBy',
            'childcareCenter',
            'room',
            'files', // Cargar archivos asociados
        ]);
        
        return $incidentReport;
    }
}
