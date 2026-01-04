<?php

namespace App\Containers\Monitoring\Attendance\UI\WEB\Controllers;

use App\Containers\Monitoring\Attendance\Actions\ListAttendancesAction;
use App\Containers\Monitoring\Attendance\UI\WEB\Requests\ListAttendancesRequest;
use App\Ship\Parents\Controllers\WebController;
use Illuminate\Http\RedirectResponse;

final class ListAttendancesController extends WebController
{
    public function __invoke(ListAttendancesRequest $request, ListAttendancesAction $action): RedirectResponse
    {
        $action->run($request);

        return back();
    }
}
