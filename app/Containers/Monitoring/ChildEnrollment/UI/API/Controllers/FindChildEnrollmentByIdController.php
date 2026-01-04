<?php

namespace App\Containers\Monitoring\ChildEnrollment\UI\API\Controllers;

use Apiato\Support\Facades\Response;
use App\Containers\Monitoring\ChildEnrollment\Actions\FindChildEnrollmentByIdAction;
use App\Containers\Monitoring\ChildEnrollment\UI\API\Requests\FindChildEnrollmentByIdRequest;
use App\Containers\Monitoring\ChildEnrollment\UI\API\Transformers\ChildEnrollmentTransformer;
use App\Ship\Parents\Controllers\ApiController;
use Illuminate\Http\JsonResponse;

final class FindChildEnrollmentByIdController extends ApiController
{
    public function __invoke(FindChildEnrollmentByIdRequest $request, FindChildEnrollmentByIdAction $action): JsonResponse
    {
        $childEnrollment = $action->run($request);

        return Response::create($childEnrollment, ChildEnrollmentTransformer::class)->ok();
    }
}
