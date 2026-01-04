<?php

namespace App\Containers\Monitoring\ChildVaccination\UI\WEB\Controllers;

use App\Containers\Monitoring\ChildVaccination\UI\WEB\Requests\CreateChildVaccinationRequest;
use App\Ship\Parents\Controllers\WebController;
use Illuminate\View\View;

final class CreateChildVaccinationController extends WebController
{
    public function __invoke(CreateChildVaccinationRequest $request): View
    {
        return view('placeholder');
    }
}
