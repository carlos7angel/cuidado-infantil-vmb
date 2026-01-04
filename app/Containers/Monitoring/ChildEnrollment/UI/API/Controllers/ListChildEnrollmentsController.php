<?php

namespace App\Containers\Monitoring\ChildEnrollment\UI\API\Controllers;

use Apiato\Support\Facades\Response;
use App\Containers\Monitoring\ChildEnrollment\Actions\ListChildEnrollmentsAction;
use App\Containers\Monitoring\ChildEnrollment\UI\API\Requests\ListChildEnrollmentsRequest;
use App\Containers\Monitoring\ChildEnrollment\UI\API\Transformers\ChildEnrollmentTransformer;
use App\Ship\Parents\Controllers\ApiController;
use Illuminate\Http\JsonResponse;

final class ListChildEnrollmentsController extends ApiController
{
    public function __invoke(ListChildEnrollmentsRequest $request, ListChildEnrollmentsAction $action): JsonResponse
    {
        $childEnrollments = $action->run($request);

        return Response::create($childEnrollments, ChildEnrollmentTransformer::class)->ok();
    }
}
