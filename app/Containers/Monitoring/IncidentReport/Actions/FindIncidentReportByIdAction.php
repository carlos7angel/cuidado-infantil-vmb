<?php

namespace App\Containers\Monitoring\IncidentReport\Actions;

use App\Containers\Monitoring\IncidentReport\Models\IncidentReport;
use App\Containers\Monitoring\IncidentReport\Tasks\FindIncidentReportByIdTask;
use App\Containers\Monitoring\IncidentReport\UI\API\Requests\FindIncidentReportByIdRequest;
use App\Ship\Parents\Actions\Action as ParentAction;

final class FindIncidentReportByIdAction extends ParentAction
{
    public function __construct(
        private readonly FindIncidentReportByIdTask $findIncidentReportByIdTask,
    ) {
    }

    public function run(FindIncidentReportByIdRequest $request): IncidentReport
    {
        return $this->findIncidentReportByIdTask->run($request->id);
    }
}
