<?php

namespace App\Containers\Monitoring\NutritionalAssessment\UI\WEB\Controllers;

use App\Containers\Monitoring\NutritionalAssessment\Actions\FindNutritionalAssessmentByIdAction;
use App\Containers\Monitoring\NutritionalAssessment\UI\WEB\Requests\FindNutritionalAssessmentByIdRequest;
use App\Ship\Parents\Controllers\WebController;
use Illuminate\Http\RedirectResponse;

final class FindNutritionalAssessmentByIdController extends WebController
{
    public function __invoke(FindNutritionalAssessmentByIdRequest $request, FindNutritionalAssessmentByIdAction $action): RedirectResponse
    {
        $action->run($request);

        return back();
    }
}
