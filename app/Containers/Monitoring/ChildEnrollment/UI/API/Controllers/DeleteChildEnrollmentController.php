<?php

namespace App\Containers\Monitoring\ChildEnrollment\UI\API\Controllers;

use Apiato\Support\Facades\Response;
use App\Containers\Monitoring\ChildEnrollment\Actions\DeleteChildEnrollmentAction;
use App\Containers\Monitoring\ChildEnrollment\UI\API\Requests\DeleteChildEnrollmentRequest;
use App\Ship\Parents\Controllers\ApiController;
use Illuminate\Http\JsonResponse;

final class DeleteChildEnrollmentController extends ApiController
{
    public function __invoke(DeleteChildEnrollmentRequest $request, DeleteChildEnrollmentAction $action): JsonResponse
    {
        $action->run($request);

        return Response::noContent();
    }
}
