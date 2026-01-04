<?php

namespace App\Containers\Monitoring\NutritionalAssessment\UI\WEB\Controllers;

use App\Containers\Monitoring\NutritionalAssessment\Actions\UpdateNutritionalAssessmentAction;
use App\Containers\Monitoring\NutritionalAssessment\UI\WEB\Requests\UpdateNutritionalAssessmentRequest;
use App\Ship\Parents\Controllers\WebController;
use Illuminate\Http\RedirectResponse;

final class UpdateNutritionalAssessmentController extends WebController
{
    public function __invoke(UpdateNutritionalAssessmentRequest $request, UpdateNutritionalAssessmentAction $action): RedirectResponse
    {
        $action->run($request);

        return back();
    }
}
