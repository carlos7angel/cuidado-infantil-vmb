<?php

namespace App\Containers\Monitoring\ChildVaccination\Actions;

use App\Containers\Monitoring\ChildVaccination\Models\ChildVaccination;
use App\Containers\Monitoring\ChildVaccination\Tasks\UpdateChildVaccinationTask;
use App\Containers\Monitoring\ChildVaccination\UI\API\Requests\UpdateChildVaccinationRequest;
use App\Ship\Parents\Actions\Action as ParentAction;

final class UpdateChildVaccinationAction extends ParentAction
{
    public function __construct(
        private readonly UpdateChildVaccinationTask $updateChildVaccinationTask,
    ) {
    }

    public function run(UpdateChildVaccinationRequest $request): ChildVaccination
    {
        $data = $request->sanitize([
            // add your request data here
        ]);

        return $this->updateChildVaccinationTask->run($data, $request->id);
    }
}
