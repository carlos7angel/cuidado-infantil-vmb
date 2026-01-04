<?php

namespace App\Containers\Monitoring\Attendance\UI\WEB\Controllers;

use App\Containers\Monitoring\Attendance\Actions\DeleteAttendanceAction;
use App\Containers\Monitoring\Attendance\UI\WEB\Requests\DeleteAttendanceRequest;
use App\Ship\Parents\Controllers\WebController;
use Illuminate\Http\RedirectResponse;

final class DeleteAttendanceController extends WebController
{
    public function __invoke(DeleteAttendanceRequest $request, DeleteAttendanceAction $action): RedirectResponse
    {
        $action->run($request);

        return back();
    }
}
