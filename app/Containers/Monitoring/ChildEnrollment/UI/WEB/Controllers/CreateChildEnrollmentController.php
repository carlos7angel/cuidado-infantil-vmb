<?php

namespace App\Containers\Monitoring\ChildEnrollment\UI\WEB\Controllers;

use App\Containers\Monitoring\ChildEnrollment\UI\WEB\Requests\CreateChildEnrollmentRequest;
use App\Ship\Parents\Controllers\WebController;
use Illuminate\View\View;

final class CreateChildEnrollmentController extends WebController
{
    public function __invoke(CreateChildEnrollmentRequest $request): View
    {
        return view('placeholder');
    }
}
