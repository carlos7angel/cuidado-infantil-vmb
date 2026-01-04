<?php

namespace App\Containers\Monitoring\ChildEnrollment\UI\WEB\Controllers;

use App\Containers\Monitoring\ChildEnrollment\Actions\CreateChildEnrollmentAction;
use App\Containers\Monitoring\ChildEnrollment\UI\WEB\Requests\StoreChildEnrollmentRequest;
use App\Ship\Parents\Controllers\WebController;
use Illuminate\Http\RedirectResponse;

final class StoreChildEnrollmentController extends WebController
{
    public function __invoke(StoreChildEnrollmentRequest $request, CreateChildEnrollmentAction $action): RedirectResponse
    {
        $action->run($request);

        return back();
    }
}
