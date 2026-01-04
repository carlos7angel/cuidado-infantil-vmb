<?php

namespace App\Containers\Monitoring\ChildEnrollment\UI\WEB\Controllers;

use App\Containers\Monitoring\ChildEnrollment\Actions\DeleteChildEnrollmentAction;
use App\Containers\Monitoring\ChildEnrollment\UI\WEB\Requests\DeleteChildEnrollmentRequest;
use App\Ship\Parents\Controllers\WebController;
use Illuminate\Http\RedirectResponse;

final class DeleteChildEnrollmentController extends WebController
{
    public function __invoke(DeleteChildEnrollmentRequest $request, DeleteChildEnrollmentAction $action): RedirectResponse
    {
        $action->run($request);

        return back();
    }
}
