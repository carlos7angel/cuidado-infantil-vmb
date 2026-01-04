<?php

namespace App\Containers\Monitoring\ChildVaccination\Actions;

use App\Containers\Monitoring\ChildVaccination\Models\ChildVaccination;
use App\Containers\Monitoring\ChildVaccination\Tasks\FindChildVaccinationByIdTask;
use App\Containers\Monitoring\ChildVaccination\UI\API\Requests\FindChildVaccinationByIdRequest;
use App\Ship\Parents\Actions\Action as ParentAction;

final class FindChildVaccinationByIdAction extends ParentAction
{
    public function __construct(
        private readonly FindChildVaccinationByIdTask $findChildVaccinationByIdTask,
    ) {
    }

    public function run(FindChildVaccinationByIdRequest $request): ChildVaccination
    {
        return $this->findChildVaccinationByIdTask->run($request->id);
    }
}
