<?php

namespace App\Containers\Monitoring\NutritionalAssessment\UI\WEB\Controllers;

use App\Containers\Monitoring\NutritionalAssessment\UI\WEB\Requests\EditNutritionalAssessmentRequest;
use App\Ship\Parents\Controllers\WebController;
use Illuminate\View\View;

final class EditNutritionalAssessmentController extends WebController
{
    public function __invoke(EditNutritionalAssessmentRequest $request): View
    {
        return view('placeholder');
    }
}
