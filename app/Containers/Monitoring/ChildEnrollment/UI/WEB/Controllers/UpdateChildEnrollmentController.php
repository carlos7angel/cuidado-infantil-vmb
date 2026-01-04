<?php

namespace App\Containers\Monitoring\ChildEnrollment\UI\WEB\Controllers;

use App\Containers\Monitoring\ChildEnrollment\Actions\UpdateChildEnrollmentAction;
use App\Containers\Monitoring\ChildEnrollment\UI\WEB\Requests\UpdateChildEnrollmentRequest;
use App\Ship\Parents\Controllers\WebController;
use Illuminate\Http\RedirectResponse;

final class UpdateChildEnrollmentController extends WebController
{
    public function __invoke(UpdateChildEnrollmentRequest $request, UpdateChildEnrollmentAction $action): RedirectResponse
    {
        $action->run($request);

        return back();
    }
}
