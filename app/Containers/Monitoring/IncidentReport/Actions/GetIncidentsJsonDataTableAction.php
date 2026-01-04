<?php

namespace App\Containers\Monitoring\IncidentReport\Actions;

use App\Containers\Frontend\Administrator\UI\WEB\Requests\Incident\GetIncidentsJsonDataTableRequest;
use App\Containers\Monitoring\IncidentReport\Tasks\GetIncidentsJsonDataTableTask;
use App\Ship\Parents\Actions\Action as ParentAction;

final class GetIncidentsJsonDataTableAction extends ParentAction
{
    public function __construct(
        private readonly GetIncidentsJsonDataTableTask $getIncidentsJsonDataTableTask,
    ) {
    }

    public function run(GetIncidentsJsonDataTableRequest $request): mixed
    {
        return $this->getIncidentsJsonDataTableTask->run($request);
    }
}

