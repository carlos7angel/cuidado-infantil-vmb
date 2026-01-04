<?php

namespace App\Containers\Monitoring\NutritionalAssessment\Actions;

use App\Containers\Monitoring\NutritionalAssessment\Tasks\ListNutritionalAssessmentsTask;
use App\Containers\Monitoring\NutritionalAssessment\UI\API\Requests\ListNutritionalAssessmentsRequest;
use App\Ship\Parents\Actions\Action as ParentAction;

final class ListNutritionalAssessmentsAction extends ParentAction
{
    public function __construct(
        private readonly ListNutritionalAssessmentsTask $listNutritionalAssessmentsTask,
    ) {
    }

    public function run(ListNutritionalAssessmentsRequest $request): mixed
    {
        return $this->listNutritionalAssessmentsTask->run();
    }
}
