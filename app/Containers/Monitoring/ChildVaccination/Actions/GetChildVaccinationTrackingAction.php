<?php

namespace App\Containers\Monitoring\ChildVaccination\Actions;

use App\Containers\Monitoring\ChildVaccination\Tasks\GetChildVaccinationTrackingTask;
use App\Containers\Monitoring\ChildVaccination\UI\API\Requests\GetChildVaccinationTrackingRequest;
use App\Ship\Parents\Actions\Action as ParentAction;

final class GetChildVaccinationTrackingAction extends ParentAction
{
    public function __construct(
        private readonly GetChildVaccinationTrackingTask $getChildVaccinationTrackingTask,
    ) {
    }

    public function run(GetChildVaccinationTrackingRequest $request): array
    {
        return $this->getChildVaccinationTrackingTask->run($request->child_id);
    }
}

