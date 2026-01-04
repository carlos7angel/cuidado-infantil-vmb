<?php

namespace App\Containers\Monitoring\Attendance\UI\WEB\Controllers;

use App\Containers\Monitoring\Attendance\Actions\UpdateAttendanceAction;
use App\Containers\Monitoring\Attendance\UI\WEB\Requests\UpdateAttendanceRequest;
use App\Ship\Parents\Controllers\WebController;
use Illuminate\Http\RedirectResponse;

final class UpdateAttendanceController extends WebController
{
    public function __invoke(UpdateAttendanceRequest $request, UpdateAttendanceAction $action): RedirectResponse
    {
        $action->run($request);

        return back();
    }
}
