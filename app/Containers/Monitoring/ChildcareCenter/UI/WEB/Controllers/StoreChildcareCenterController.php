<?php

namespace App\Containers\Monitoring\ChildcareCenter\UI\WEB\Controllers;

use App\Containers\Monitoring\ChildcareCenter\Actions\CreateChildcareCenterAction;
use App\Containers\Monitoring\ChildcareCenter\UI\WEB\Requests\StoreChildcareCenterRequest;
use App\Ship\Parents\Controllers\WebController;
use Illuminate\Http\RedirectResponse;

final class StoreChildcareCenterController extends WebController
{
    public function __invoke(StoreChildcareCenterRequest $request, CreateChildcareCenterAction $action): RedirectResponse
    {
        $action->run($request);

        return back();
    }
}
