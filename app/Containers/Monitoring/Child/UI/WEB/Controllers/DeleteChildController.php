<?php

namespace App\Containers\Monitoring\Child\UI\WEB\Controllers;

use App\Containers\Monitoring\Child\Actions\DeleteChildAction;
use App\Containers\Monitoring\Child\UI\WEB\Requests\DeleteChildRequest;
use App\Ship\Parents\Controllers\WebController;
use Illuminate\Http\RedirectResponse;

final class DeleteChildController extends WebController
{
    public function __invoke(DeleteChildRequest $request, DeleteChildAction $action): RedirectResponse
    {
        $action->run($request);

        return back();
    }
}
