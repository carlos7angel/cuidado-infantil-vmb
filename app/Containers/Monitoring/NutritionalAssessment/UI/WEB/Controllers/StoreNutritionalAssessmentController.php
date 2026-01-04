<?php

namespace App\Containers\Monitoring\NutritionalAssessment\UI\WEB\Controllers;

use App\Containers\Monitoring\NutritionalAssessment\Actions\CreateNutritionalAssessmentAction;
use App\Containers\Monitoring\NutritionalAssessment\UI\WEB\Requests\StoreNutritionalAssessmentRequest;
use App\Ship\Parents\Controllers\WebController;
use Illuminate\Http\RedirectResponse;

final class StoreNutritionalAssessmentController extends WebController
{
    public function __invoke(StoreNutritionalAssessmentRequest $request, CreateNutritionalAssessmentAction $action): RedirectResponse
    {
        $action->run($request);

        return back();
    }
}
