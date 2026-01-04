<?php

namespace App\Containers\Monitoring\ChildDevelopment\UI\API\Controllers;

use Apiato\Support\Facades\Response;
use App\Containers\Monitoring\ChildDevelopment\Actions\ListChildDevelopmentEvaluationsAction;
use App\Containers\Monitoring\ChildDevelopment\UI\API\Requests\ListChildDevelopmentEvaluationsRequest;
use App\Containers\Monitoring\ChildDevelopment\UI\API\Transformers\ChildDevelopmentListTransformer;
use App\Ship\Parents\Controllers\ApiController;
use Illuminate\Http\JsonResponse;

final class ListChildDevelopmentEvaluationsController extends ApiController
{
    public function __invoke(
        ListChildDevelopmentEvaluationsRequest $request,
        ListChildDevelopmentEvaluationsAction $action
    ): JsonResponse {
        $evaluations = $action->run($request);

        return Response::create($evaluations, ChildDevelopmentListTransformer::class)->ok();
    }
}

