<?php

namespace App\Containers\Monitoring\NutritionalAssessment\UI\WEB\Controllers;

use App\Containers\Monitoring\NutritionalAssessment\Actions\ListNutritionalAssessmentsAction;
use App\Containers\Monitoring\NutritionalAssessment\UI\WEB\Requests\ListNutritionalAssessmentsRequest;
use App\Ship\Parents\Controllers\WebController;
use Illuminate\Http\RedirectResponse;

final class ListNutritionalAssessmentsController extends WebController
{
    public function __invoke(ListNutritionalAssessmentsRequest $request, ListNutritionalAssessmentsAction $action): RedirectResponse
    {
        $action->run($request);

        return back();
    }
}
