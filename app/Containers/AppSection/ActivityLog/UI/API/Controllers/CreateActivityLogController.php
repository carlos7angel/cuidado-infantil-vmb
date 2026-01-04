<?php

namespace App\Containers\AppSection\ActivityLog\UI\API\Controllers;

use Apiato\Support\Facades\Response;
use App\Containers\AppSection\ActivityLog\Actions\CreateActivityLogAction;
use App\Containers\AppSection\ActivityLog\UI\API\Requests\CreateActivityLogRequest;
use App\Containers\AppSection\ActivityLog\UI\API\Transformers\ActivityLogTransformer;
use App\Ship\Parents\Controllers\ApiController;
use Illuminate\Http\JsonResponse;

final class CreateActivityLogController extends ApiController
{
    public function __invoke(CreateActivityLogRequest $request, CreateActivityLogAction $action): JsonResponse
    {
        $activityLog = $action->run($request);

        return Response::create($activityLog, ActivityLogTransformer::class)->created();
    }
}
