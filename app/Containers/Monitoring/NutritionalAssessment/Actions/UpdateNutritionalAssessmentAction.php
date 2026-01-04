<?php

namespace App\Containers\Monitoring\NutritionalAssessment\Actions;

use App\Containers\Monitoring\NutritionalAssessment\Models\NutritionalAssessment;
use App\Containers\Monitoring\NutritionalAssessment\Tasks\UpdateNutritionalAssessmentTask;
use App\Containers\Monitoring\NutritionalAssessment\UI\API\Requests\UpdateNutritionalAssessmentRequest;
use App\Ship\Parents\Actions\Action as ParentAction;

final class UpdateNutritionalAssessmentAction extends ParentAction
{
    public function __construct(
        private readonly UpdateNutritionalAssessmentTask $updateNutritionalAssessmentTask,
    ) {
    }

    public function run(UpdateNutritionalAssessmentRequest $request): NutritionalAssessment
    {
        $data = $request->sanitize([
            // add your request data here
        ]);

        return $this->updateNutritionalAssessmentTask->run($data, $request->id);
    }
}
