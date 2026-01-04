<?php

namespace App\Containers\Monitoring\ChildEnrollment\UI\API\Controllers;

use Apiato\Support\Facades\Response;
use App\Containers\Monitoring\ChildEnrollment\Actions\UpdateChildEnrollmentAction;
use App\Containers\Monitoring\ChildEnrollment\UI\API\Requests\UpdateChildEnrollmentRequest;
use App\Containers\Monitoring\ChildEnrollment\UI\API\Transformers\ChildEnrollmentTransformer;
use App\Ship\Parents\Controllers\ApiController;
use Illuminate\Http\JsonResponse;

final class UpdateChildEnrollmentController extends ApiController
{
    public function __invoke(UpdateChildEnrollmentRequest $request, UpdateChildEnrollmentAction $action): JsonResponse
    {
        $childEnrollment = $action->run($request);

        return Response::create($childEnrollment, ChildEnrollmentTransformer::class)->ok();
    }
}
