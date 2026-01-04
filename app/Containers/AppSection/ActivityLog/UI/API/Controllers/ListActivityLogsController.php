<?php

namespace App\Containers\AppSection\ActivityLog\UI\API\Controllers;

use Apiato\Support\Facades\Response;
use App\Containers\AppSection\ActivityLog\Actions\ListActivityLogsAction;
use App\Containers\AppSection\ActivityLog\UI\API\Requests\ListActivityLogsRequest;
use App\Containers\AppSection\ActivityLog\UI\API\Transformers\ActivityLogTransformer;
use App\Ship\Parents\Controllers\ApiController;
use Illuminate\Http\JsonResponse;

final class ListActivityLogsController extends ApiController
{
    public function __invoke(ListActivityLogsRequest $request, ListActivityLogsAction $action): JsonResponse
    {
        $activityLogs = $action->run($request);

        return Response::create($activityLogs, ActivityLogTransformer::class)->ok();
    }
}
