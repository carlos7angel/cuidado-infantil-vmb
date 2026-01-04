<?php

namespace App\Containers\Monitoring\ChildVaccination\UI\WEB\Controllers;

use App\Containers\Monitoring\ChildVaccination\Actions\FindChildVaccinationByIdAction;
use App\Containers\Monitoring\ChildVaccination\UI\WEB\Requests\FindChildVaccinationByIdRequest;
use App\Ship\Parents\Controllers\WebController;
use Illuminate\Http\RedirectResponse;

final class FindChildVaccinationByIdController extends WebController
{
    public function __invoke(FindChildVaccinationByIdRequest $request, FindChildVaccinationByIdAction $action): RedirectResponse
    {
        $action->run($request);

        return back();
    }
}
