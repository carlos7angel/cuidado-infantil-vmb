<?php

namespace App\Containers\Monitoring\ChildEnrollment\UI\API\Controllers;

use Apiato\Support\Facades\Response;
use App\Containers\Monitoring\ChildEnrollment\Actions\CreateChildEnrollmentAction;
use App\Containers\Monitoring\ChildEnrollment\UI\API\Requests\CreateChildEnrollmentRequest;
use App\Containers\Monitoring\ChildEnrollment\UI\API\Transformers\ChildEnrollmentTransformer;
use App\Ship\Parents\Controllers\ApiController;
use Illuminate\Http\JsonResponse;

final class CreateChildEnrollmentController extends ApiController
{
    public function __invoke(CreateChildEnrollmentRequest $request, CreateChildEnrollmentAction $action): JsonResponse
    {
        $childEnrollment = $action->run($request);

        return Response::create($childEnrollment, ChildEnrollmentTransformer::class)->created();
    }
}
