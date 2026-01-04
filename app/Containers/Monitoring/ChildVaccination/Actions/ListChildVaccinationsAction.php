<?php

namespace App\Containers\Monitoring\ChildVaccination\Actions;

use App\Containers\Monitoring\ChildVaccination\Tasks\ListChildVaccinationsTask;
use App\Containers\Monitoring\ChildVaccination\UI\API\Requests\ListChildVaccinationsRequest;
use App\Ship\Parents\Actions\Action as ParentAction;

final class ListChildVaccinationsAction extends ParentAction
{
    public function __construct(
        private readonly ListChildVaccinationsTask $listChildVaccinationsTask,
    ) {
    }

    public function run(ListChildVaccinationsRequest $request): mixed
    {
        return $this->listChildVaccinationsTask->run();
    }
}
