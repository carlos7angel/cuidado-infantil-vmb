<?php

namespace App\Containers\Monitoring\ChildVaccination\UI\WEB\Controllers;

use App\Containers\Monitoring\ChildVaccination\UI\WEB\Requests\EditChildVaccinationRequest;
use App\Ship\Parents\Controllers\WebController;
use Illuminate\View\View;

final class EditChildVaccinationController extends WebController
{
    public function __invoke(EditChildVaccinationRequest $request): View
    {
        return view('placeholder');
    }
}
