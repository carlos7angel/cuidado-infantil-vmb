<?php

namespace App\Containers\Monitoring\IncidentReport\Actions;

use App\Containers\Monitoring\IncidentReport\Tasks\DeleteIncidentReportTask;
use App\Containers\Monitoring\IncidentReport\UI\API\Requests\DeleteIncidentReportRequest;
use App\Ship\Parents\Actions\Action as ParentAction;

final class DeleteIncidentReportAction extends ParentAction
{
    public function __construct(
        private readonly DeleteIncidentReportTask $deleteIncidentReportTask,
    ) {
    }

    public function run(DeleteIncidentReportRequest $request): bool
    {
        return $this->deleteIncidentReportTask->run($request->id);
    }
}
