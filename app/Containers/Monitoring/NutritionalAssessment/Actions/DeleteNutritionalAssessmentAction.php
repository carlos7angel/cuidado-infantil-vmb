<?php

namespace App\Containers\Monitoring\NutritionalAssessment\Actions;

use App\Containers\Monitoring\NutritionalAssessment\Tasks\DeleteNutritionalAssessmentTask;
use App\Containers\Monitoring\NutritionalAssessment\UI\API\Requests\DeleteNutritionalAssessmentRequest;
use App\Ship\Parents\Actions\Action as ParentAction;

final class DeleteNutritionalAssessmentAction extends ParentAction
{
    public function __construct(
        private readonly DeleteNutritionalAssessmentTask $deleteNutritionalAssessmentTask,
    ) {
    }

    public function run(DeleteNutritionalAssessmentRequest $request): bool
    {
        return $this->deleteNutritionalAssessmentTask->run($request->id);
    }
}
