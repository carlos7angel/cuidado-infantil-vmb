<?php

namespace App\Containers\Monitoring\ChildVaccination\UI\WEB\Controllers;

use App\Containers\Monitoring\ChildVaccination\Actions\DeleteChildVaccinationAction;
use App\Containers\Monitoring\ChildVaccination\UI\WEB\Requests\DeleteChildVaccinationRequest;
use App\Ship\Parents\Controllers\WebController;
use Illuminate\Http\RedirectResponse;

final class DeleteChildVaccinationController extends WebController
{
    public function __invoke(DeleteChildVaccinationRequest $request, DeleteChildVaccinationAction $action): RedirectResponse
    {
        $action->run($request);

        return back();
    }
}
