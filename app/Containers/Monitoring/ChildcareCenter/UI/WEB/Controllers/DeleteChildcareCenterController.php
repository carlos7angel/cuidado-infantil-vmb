<?php

namespace App\Containers\Monitoring\ChildcareCenter\UI\WEB\Controllers;

use App\Containers\Monitoring\ChildcareCenter\Actions\DeleteChildcareCenterAction;
use App\Containers\Monitoring\ChildcareCenter\UI\WEB\Requests\DeleteChildcareCenterRequest;
use App\Ship\Parents\Controllers\WebController;
use Illuminate\Http\RedirectResponse;

final class DeleteChildcareCenterController extends WebController
{
    public function __invoke(DeleteChildcareCenterRequest $request, DeleteChildcareCenterAction $action): RedirectResponse
    {
        $action->run($request);

        return back();
    }
}
