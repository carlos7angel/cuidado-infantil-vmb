<?php

namespace App\Containers\Monitoring\ChildcareCenter\UI\WEB\Controllers;

use App\Containers\Monitoring\ChildcareCenter\Actions\ListChildcareCentersAction;
use App\Containers\Monitoring\ChildcareCenter\UI\WEB\Requests\ListChildcareCentersRequest;
use App\Ship\Parents\Controllers\WebController;
use Illuminate\Http\RedirectResponse;

final class ListChildcareCentersController extends WebController
{
    public function __invoke(ListChildcareCentersRequest $request, ListChildcareCentersAction $action): RedirectResponse
    {
        $action->run($request);

        return back();
    }
}
