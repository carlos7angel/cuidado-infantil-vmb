<?php

namespace App\Containers\Monitoring\Child\UI\WEB\Controllers;

use App\Containers\Monitoring\Child\Actions\CreateChildAction;
use App\Containers\Monitoring\Child\UI\WEB\Requests\StoreChildRequest;
use App\Ship\Parents\Controllers\WebController;
use Illuminate\Http\RedirectResponse;

final class StoreChildController extends WebController
{
    public function __invoke(StoreChildRequest $request, CreateChildAction $action): RedirectResponse
    {
        $action->run($request);

        return back();
    }
}
