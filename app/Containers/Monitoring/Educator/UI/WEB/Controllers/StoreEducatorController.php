<?php

namespace App\Containers\Monitoring\Educator\UI\WEB\Controllers;

use App\Containers\Monitoring\Educator\Actions\CreateEducatorAction;
use App\Containers\Monitoring\Educator\UI\WEB\Requests\StoreEducatorRequest;
use App\Ship\Parents\Controllers\WebController;
use Illuminate\Http\RedirectResponse;

final class StoreEducatorController extends WebController
{
    public function __invoke(StoreEducatorRequest $request, CreateEducatorAction $action): RedirectResponse
    {
        $action->run($request);

        return back();
    }
}
