<?php

namespace App\Containers\Monitoring\Attendance\UI\WEB\Controllers;

use App\Containers\Monitoring\Attendance\Actions\CreateAttendanceAction;
use App\Containers\Monitoring\Attendance\UI\WEB\Requests\StoreAttendanceRequest;
use App\Ship\Parents\Controllers\WebController;
use Illuminate\Http\RedirectResponse;

final class StoreAttendanceController extends WebController
{
    public function __invoke(StoreAttendanceRequest $request, CreateAttendanceAction $action): RedirectResponse
    {
        $action->run($request);

        return back();
    }
}
