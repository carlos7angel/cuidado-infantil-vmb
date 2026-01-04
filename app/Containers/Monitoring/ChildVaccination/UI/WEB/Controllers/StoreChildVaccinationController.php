<?php

namespace App\Containers\Monitoring\ChildVaccination\UI\WEB\Controllers;

use App\Containers\Monitoring\ChildVaccination\Actions\CreateChildVaccinationAction;
use App\Containers\Monitoring\ChildVaccination\UI\WEB\Requests\StoreChildVaccinationRequest;
use App\Ship\Parents\Controllers\WebController;
use Illuminate\Http\RedirectResponse;

final class StoreChildVaccinationController extends WebController
{
    public function __invoke(StoreChildVaccinationRequest $request, CreateChildVaccinationAction $action): RedirectResponse
    {
        $action->run($request);

        return back();
    }
}
