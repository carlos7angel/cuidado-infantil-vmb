<?php

namespace App\Containers\Monitoring\Child\UI\WEB\Controllers;

use App\Containers\Monitoring\Child\Actions\ListChildrenAction;
use App\Containers\Monitoring\Child\UI\WEB\Requests\ListChildrenRequest;
use App\Ship\Parents\Controllers\WebController;
use Illuminate\Http\RedirectResponse;

final class ListChildrenController extends WebController
{
    public function __invoke(ListChildrenRequest $request, ListChildrenAction $action): RedirectResponse
    {
        $action->run($request);

        return back();
    }
}
