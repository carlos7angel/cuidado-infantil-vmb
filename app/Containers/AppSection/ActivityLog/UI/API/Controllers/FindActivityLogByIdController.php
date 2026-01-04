<?php

namespace App\Containers\AppSection\ActivityLog\UI\API\Controllers;

use Apiato\Support\Facades\Response;
use App\Containers\AppSection\ActivityLog\Actions\FindActivityLogByIdAction;
use App\Containers\AppSection\ActivityLog\UI\API\Requests\FindActivityLogByIdRequest;
use App\Containers\AppSection\ActivityLog\UI\API\Transformers\ActivityLogTransformer;
use App\Ship\Parents\Controllers\ApiController;
use Illuminate\Http\JsonResponse;

final class FindActivityLogByIdController extends ApiController
{
    public function __invoke(FindActivityLogByIdRequest $request, FindActivityLogByIdAction $action): JsonResponse
    {
        $activityLog = $action->run($request);

        return Response::create($activityLog, ActivityLogTransformer::class)->ok();
    }
}
