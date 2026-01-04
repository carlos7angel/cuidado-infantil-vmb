<?php

namespace App\Containers\Monitoring\Attendance\UI\WEB\Controllers;

use App\Containers\Monitoring\Attendance\UI\WEB\Requests\EditAttendanceRequest;
use App\Ship\Parents\Controllers\WebController;
use Illuminate\View\View;

final class EditAttendanceController extends WebController
{
    public function __invoke(EditAttendanceRequest $request): View
    {
        return view('placeholder');
    }
}
