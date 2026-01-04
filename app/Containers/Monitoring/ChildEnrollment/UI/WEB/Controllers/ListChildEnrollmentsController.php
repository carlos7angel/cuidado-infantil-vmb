<?php

namespace App\Containers\Monitoring\ChildEnrollment\UI\WEB\Controllers;

use App\Containers\Monitoring\ChildEnrollment\Actions\ListChildEnrollmentsAction;
use App\Containers\Monitoring\ChildEnrollment\UI\WEB\Requests\ListChildEnrollmentsRequest;
use App\Ship\Parents\Controllers\WebController;
use Illuminate\Http\RedirectResponse;

final class ListChildEnrollmentsController extends WebController
{
    public function __invoke(ListChildEnrollmentsRequest $request, ListChildEnrollmentsAction $action): RedirectResponse
    {
        $action->run($request);

        return back();
    }
}
