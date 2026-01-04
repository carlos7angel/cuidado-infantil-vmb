<?php

namespace App\Containers\Monitoring\ChildVaccination\UI\WEB\Controllers;

use App\Containers\Monitoring\ChildVaccination\Actions\UpdateChildVaccinationAction;
use App\Containers\Monitoring\ChildVaccination\UI\WEB\Requests\UpdateChildVaccinationRequest;
use App\Ship\Parents\Controllers\WebController;
use Illuminate\Http\RedirectResponse;

final class UpdateChildVaccinationController extends WebController
{
    public function __invoke(UpdateChildVaccinationRequest $request, UpdateChildVaccinationAction $action): RedirectResponse
    {
        $action->run($request);

        return back();
    }
}
