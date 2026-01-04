<?php

namespace App\Containers\Monitoring\ChildDevelopment\UI\API\Controllers;

use Apiato\Support\Facades\Response;
use App\Containers\Monitoring\ChildDevelopment\Actions\FindChildDevelopmentByIdAction;
use App\Containers\Monitoring\ChildDevelopment\UI\API\Requests\FindChildDevelopmentByIdRequest;
use App\Containers\Monitoring\ChildDevelopment\UI\API\Transformers\ChildDevelopmentTransformer;
use App\Ship\Parents\Controllers\ApiController;
use Illuminate\Http\JsonResponse;

final class FindChildDevelopmentEvaluationByIdController extends ApiController
{
    public function __invoke(
        FindChildDevelopmentByIdRequest $request,
        FindChildDevelopmentByIdAction $action
    ): JsonResponse {
        $evaluation = $action->run($request);

        return Response::create($evaluation, ChildDevelopmentTransformer::class)->ok();
    }
}

