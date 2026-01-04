<?php

namespace App\Containers\Monitoring\ChildDevelopment\UI\API\Controllers;

use Apiato\Support\Facades\Response;
use App\Containers\Monitoring\ChildDevelopment\Actions\CreateChildDevelopmentAction;
use App\Containers\Monitoring\ChildDevelopment\UI\API\Requests\CreateChildDevelopmentRequest;
use App\Containers\Monitoring\ChildDevelopment\UI\API\Transformers\ChildDevelopmentListTransformer;
use App\Ship\Parents\Controllers\ApiController;
use Illuminate\Http\JsonResponse;

final class CreateChildDevelopmentEvaluationController extends ApiController
{
    public function __invoke(
        CreateChildDevelopmentRequest $request,
        CreateChildDevelopmentAction $action
    ): JsonResponse {
        $evaluation = $action->run($request);

        // Retornar información resumida (como en el listado) pero con overall_score y overall_status
        return Response::create($evaluation, ChildDevelopmentListTransformer::class)->created();
    }
}

