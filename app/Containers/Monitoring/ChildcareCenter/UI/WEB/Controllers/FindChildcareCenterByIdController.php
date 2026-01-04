<?php

namespace App\Containers\Monitoring\ChildcareCenter\UI\WEB\Controllers;

use App\Containers\Monitoring\ChildcareCenter\Actions\FindChildcareCenterByIdAction;
use App\Containers\Monitoring\ChildcareCenter\UI\WEB\Requests\FindChildcareCenterByIdRequest;
use App\Ship\Parents\Controllers\WebController;
use Illuminate\Http\RedirectResponse;

final class FindChildcareCenterByIdController extends WebController
{
    public function __invoke(FindChildcareCenterByIdRequest $request, FindChildcareCenterByIdAction $action): RedirectResponse
    {
        $action->run($request);

        return back();
    }
}
