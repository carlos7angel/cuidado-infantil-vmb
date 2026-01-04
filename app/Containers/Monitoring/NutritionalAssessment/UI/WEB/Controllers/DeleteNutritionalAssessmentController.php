<?php

namespace App\Containers\Monitoring\NutritionalAssessment\UI\WEB\Controllers;

use App\Containers\Monitoring\NutritionalAssessment\Actions\DeleteNutritionalAssessmentAction;
use App\Containers\Monitoring\NutritionalAssessment\UI\WEB\Requests\DeleteNutritionalAssessmentRequest;
use App\Ship\Parents\Controllers\WebController;
use Illuminate\Http\RedirectResponse;

final class DeleteNutritionalAssessmentController extends WebController
{
    public function __invoke(DeleteNutritionalAssessmentRequest $request, DeleteNutritionalAssessmentAction $action): RedirectResponse
    {
        $action->run($request);

        return back();
    }
}
