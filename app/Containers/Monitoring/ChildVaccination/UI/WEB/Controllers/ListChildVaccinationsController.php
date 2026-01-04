<?php

namespace App\Containers\Monitoring\ChildVaccination\UI\WEB\Controllers;

use App\Containers\Monitoring\ChildVaccination\Actions\ListChildVaccinationsAction;
use App\Containers\Monitoring\ChildVaccination\UI\WEB\Requests\ListChildVaccinationsRequest;
use App\Ship\Parents\Controllers\WebController;
use Illuminate\Http\RedirectResponse;

final class ListChildVaccinationsController extends WebController
{
    public function __invoke(ListChildVaccinationsRequest $request, ListChildVaccinationsAction $action): RedirectResponse
    {
        $action->run($request);

        return back();
    }
}
