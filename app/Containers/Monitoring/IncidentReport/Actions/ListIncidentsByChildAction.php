<?php

namespace App\Containers\Monitoring\IncidentReport\Actions;

use App\Containers\Monitoring\IncidentReport\Tasks\ListIncidentsByChildTask;
use App\Ship\Parents\Actions\Action as ParentAction;
use App\Ship\Parents\Requests\Request;

final class ListIncidentsByChildAction extends ParentAction
{
    public function __construct(
        private readonly ListIncidentsByChildTask $listIncidentsByChildTask,
    ) {
    }

    public function run(Request $request): mixed
    {
        $childId = $request->route('child_id');
        
        return $this->listIncidentsByChildTask->run($childId);
    }
}
