<?php

namespace App\Containers\Monitoring\Educator\UI\WEB\Controllers;

use App\Containers\Monitoring\Educator\Actions\ListEducatorsAction;
use App\Containers\Monitoring\Educator\UI\WEB\Requests\ListEducatorsRequest;
use App\Ship\Parents\Controllers\WebController;
use Illuminate\Http\RedirectResponse;

final class ListEducatorsController extends WebController
{
    public function __invoke(ListEducatorsRequest $request, ListEducatorsAction $action): RedirectResponse
    {
        $action->run($request);

        return back();
    }
}
