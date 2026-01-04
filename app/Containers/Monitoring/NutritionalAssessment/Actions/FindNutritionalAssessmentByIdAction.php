<?php

namespace App\Containers\Monitoring\NutritionalAssessment\Actions;

use App\Containers\Monitoring\NutritionalAssessment\Models\NutritionalAssessment;
use App\Containers\Monitoring\NutritionalAssessment\Tasks\FindNutritionalAssessmentByIdTask;
use App\Containers\Monitoring\NutritionalAssessment\UI\API\Requests\FindNutritionalAssessmentByIdRequest;
use App\Ship\Parents\Actions\Action as ParentAction;

final class FindNutritionalAssessmentByIdAction extends ParentAction
{
    public function __construct(
        private readonly FindNutritionalAssessmentByIdTask $findNutritionalAssessmentByIdTask,
    ) {
    }

    public function run(FindNutritionalAssessmentByIdRequest $request): NutritionalAssessment
    {
        return $this->findNutritionalAssessmentByIdTask->run($request->id);
    }
}
