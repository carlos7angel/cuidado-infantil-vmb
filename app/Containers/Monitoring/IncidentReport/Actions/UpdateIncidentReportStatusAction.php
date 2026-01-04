<?php

namespace App\Containers\Monitoring\IncidentReport\Actions;

use App\Containers\Frontend\Administrator\UI\WEB\Requests\Incident\UpdateIncidentStatusRequest;
use App\Containers\Monitoring\IncidentReport\Models\IncidentReport;
use App\Containers\Monitoring\IncidentReport\Tasks\UpdateIncidentReportStatusTask;
use App\Ship\Parents\Actions\Action as ParentAction;

final class UpdateIncidentReportStatusAction extends ParentAction
{
    public function __construct(
        private readonly UpdateIncidentReportStatusTask $updateIncidentReportStatusTask,
    ) {
    }

    public function run(UpdateIncidentStatusRequest $request): IncidentReport
    {
        return $this->updateIncidentReportStatusTask->run(
            $request->input('status'),
            $request->incident_id
        );
    }
}

