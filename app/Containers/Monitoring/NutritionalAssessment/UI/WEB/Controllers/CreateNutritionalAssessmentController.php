<?php

namespace App\Containers\Monitoring\NutritionalAssessment\UI\WEB\Controllers;

use App\Containers\Monitoring\NutritionalAssessment\UI\WEB\Requests\CreateNutritionalAssessmentRequest;
use App\Ship\Parents\Controllers\WebController;
use Illuminate\View\View;

final class CreateNutritionalAssessmentController extends WebController
{
    public function __invoke(CreateNutritionalAssessmentRequest $request): View
    {
        return view('placeholder');
    }
}
