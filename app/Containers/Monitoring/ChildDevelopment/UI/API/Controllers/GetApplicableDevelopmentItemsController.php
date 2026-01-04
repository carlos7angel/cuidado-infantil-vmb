<?php

namespace App\Containers\Monitoring\ChildDevelopment\UI\API\Controllers;

use App\Containers\Monitoring\ChildDevelopment\Actions\GetApplicableDevelopmentItemsAction;
use App\Containers\Monitoring\ChildDevelopment\UI\API\Requests\GetApplicableDevelopmentItemsRequest;
use App\Ship\Parents\Controllers\ApiController;
use Illuminate\Http\JsonResponse;

final class GetApplicableDevelopmentItemsController extends ApiController
{
    public function __invoke(
        GetApplicableDevelopmentItemsRequest $request,
        GetApplicableDevelopmentItemsAction $action
    ): JsonResponse {
        $data = $action->run($request);

        return response()->json([
            'data' => $data,
        ]);
    }
}

