<?php

namespace App\Containers\AppSection\ActivityLog\UI\API\Controllers;

use Apiato\Support\Facades\Response;
use App\Containers\AppSection\ActivityLog\Actions\UpdateActivityLogAction;
use App\Containers\AppSection\ActivityLog\UI\API\Requests\UpdateActivityLogRequest;
use App\Containers\AppSection\ActivityLog\UI\API\Transformers\ActivityLogTransformer;
use App\Ship\Parents\Controllers\ApiController;
use Illuminate\Http\JsonResponse;

final class UpdateActivityLogController extends ApiController
{
    public function __invoke(UpdateActivityLogRequest $request, UpdateActivityLogAction $action): JsonResponse
    {
        $activityLog = $action->run($request);

        return Response::create($activityLog, ActivityLogTransformer::class)->ok();
    }
}
