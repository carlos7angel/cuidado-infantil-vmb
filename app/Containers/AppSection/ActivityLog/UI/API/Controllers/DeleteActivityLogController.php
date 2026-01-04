<?php

namespace App\Containers\AppSection\ActivityLog\UI\API\Controllers;

use Apiato\Support\Facades\Response;
use App\Containers\AppSection\ActivityLog\Actions\DeleteActivityLogAction;
use App\Containers\AppSection\ActivityLog\UI\API\Requests\DeleteActivityLogRequest;
use App\Ship\Parents\Controllers\ApiController;
use Illuminate\Http\JsonResponse;

final class DeleteActivityLogController extends ApiController
{
    public function __invoke(DeleteActivityLogRequest $request, DeleteActivityLogAction $action): JsonResponse
    {
        $action->run($request);

        return Response::noContent();
    }
}
