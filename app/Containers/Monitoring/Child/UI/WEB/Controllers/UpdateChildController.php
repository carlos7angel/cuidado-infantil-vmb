<?php

namespace App\Containers\Monitoring\Child\UI\WEB\Controllers;

use App\Containers\Monitoring\Child\Actions\UpdateChildAction;
use App\Containers\Monitoring\Child\UI\WEB\Requests\UpdateChildRequest;
use App\Ship\Parents\Controllers\WebController;
use Illuminate\Http\RedirectResponse;

final class UpdateChildController extends WebController
{
    public function __invoke(UpdateChildRequest $request, UpdateChildAction $action): RedirectResponse
    {
        $action->run($request);

        return back();
    }
}
