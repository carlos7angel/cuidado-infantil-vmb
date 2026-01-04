<?php

namespace App\Containers\Monitoring\ChildEnrollment\UI\WEB\Controllers;

use App\Containers\Monitoring\ChildEnrollment\UI\WEB\Requests\EditChildEnrollmentRequest;
use App\Ship\Parents\Controllers\WebController;
use Illuminate\View\View;

final class EditChildEnrollmentController extends WebController
{
    public function __invoke(EditChildEnrollmentRequest $request): View
    {
        return view('placeholder');
    }
}
