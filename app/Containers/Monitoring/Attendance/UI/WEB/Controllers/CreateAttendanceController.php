<?php

namespace App\Containers\Monitoring\Attendance\UI\WEB\Controllers;

use App\Containers\Monitoring\Attendance\UI\WEB\Requests\CreateAttendanceRequest;
use App\Ship\Parents\Controllers\WebController;
use Illuminate\View\View;

final class CreateAttendanceController extends WebController
{
    public function __invoke(CreateAttendanceRequest $request): View
    {
        return view('placeholder');
    }
}
