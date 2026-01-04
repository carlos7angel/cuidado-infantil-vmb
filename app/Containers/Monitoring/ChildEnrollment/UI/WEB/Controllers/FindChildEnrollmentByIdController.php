<?php

namespace App\Containers\Monitoring\ChildEnrollment\UI\WEB\Controllers;

use App\Containers\Monitoring\ChildEnrollment\Actions\FindChildEnrollmentByIdAction;
use App\Containers\Monitoring\ChildEnrollment\UI\WEB\Requests\FindChildEnrollmentByIdRequest;
use App\Ship\Parents\Controllers\WebController;
use Illuminate\Http\RedirectResponse;

final class FindChildEnrollmentByIdController extends WebController
{
    public function __invoke(FindChildEnrollmentByIdRequest $request, FindChildEnrollmentByIdAction $action): RedirectResponse
    {
        $action->run($request);

        return back();
    }
}
