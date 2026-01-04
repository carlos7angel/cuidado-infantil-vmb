<?php

namespace App\Containers\Monitoring\ChildcareCenter\UI\WEB\Controllers;

use App\Containers\Monitoring\ChildcareCenter\Actions\UpdateChildcareCenterAction;
use App\Containers\Monitoring\ChildcareCenter\UI\WEB\Requests\UpdateChildcareCenterRequest;
use App\Ship\Parents\Controllers\WebController;
use Illuminate\Http\RedirectResponse;

final class UpdateChildcareCenterController extends WebController
{
    public function __invoke(UpdateChildcareCenterRequest $request, UpdateChildcareCenterAction $action): RedirectResponse
    {
        $action->run($request);

        return back();
    }
}
