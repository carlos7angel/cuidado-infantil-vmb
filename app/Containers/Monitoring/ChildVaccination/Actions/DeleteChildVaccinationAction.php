<?php

namespace App\Containers\Monitoring\ChildVaccination\Actions;

use App\Containers\Monitoring\ChildVaccination\Tasks\DeleteChildVaccinationTask;
use App\Containers\Monitoring\ChildVaccination\UI\API\Requests\DeleteChildVaccinationRequest;
use App\Ship\Parents\Actions\Action as ParentAction;

final class DeleteChildVaccinationAction extends ParentAction
{
    public function __construct(
        private readonly DeleteChildVaccinationTask $deleteChildVaccinationTask,
    ) {
    }

    public function run(DeleteChildVaccinationRequest $request): bool
    {
        return $this->deleteChildVaccinationTask->run($request->id);
    }
}
