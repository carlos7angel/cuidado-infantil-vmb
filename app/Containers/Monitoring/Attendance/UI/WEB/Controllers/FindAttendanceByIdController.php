<?php

namespace App\Containers\Monitoring\Attendance\UI\WEB\Controllers;

use App\Containers\Monitoring\Attendance\Actions\FindAttendanceByIdAction;
use App\Containers\Monitoring\Attendance\UI\WEB\Requests\FindAttendanceByIdRequest;
use App\Ship\Parents\Controllers\WebController;
use Illuminate\Http\RedirectResponse;

final class FindAttendanceByIdController extends WebController
{
    public function __invoke(FindAttendanceByIdRequest $request, FindAttendanceByIdAction $action): RedirectResponse
    {
        $action->run($request);

        return back();
    }
}
