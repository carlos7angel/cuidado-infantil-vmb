<?php

namespace App\Containers\Monitoring\IncidentReport\Actions;

use App\Containers\Monitoring\IncidentReport\Tasks\ListIncidentReportsTask;
use App\Containers\Monitoring\IncidentReport\UI\API\Requests\ListIncidentReportsRequest;
use App\Ship\Parents\Actions\Action as ParentAction;

final class ListIncidentReportsAction extends ParentAction
{
    public function __construct(
        private readonly ListIncidentReportsTask $listIncidentReportsTask,
    ) {
    }

    public function run(ListIncidentReportsRequest $request): mixed
    {
        $childcareCenterId = $request->route('childcare_center_id') ?? $request->input('childcare_center_id');
        
        return $this->listIncidentReportsTask->run($childcareCenterId);
    }
}
