<?php

namespace App\Containers\Monitoring\NutritionalAssessment\UI\API\Controllers;

use Apiato\Support\Facades\Response;
use App\Containers\Monitoring\NutritionalAssessment\Actions\FindNutritionalAssessmentByIdAction;
use App\Containers\Monitoring\NutritionalAssessment\UI\API\Requests\FindNutritionalAssessmentByIdRequest;
use App\Containers\Monitoring\NutritionalAssessment\UI\API\Transformers\NutritionalAssessmentTransformer;
use App\Ship\Parents\Controllers\ApiController;
use Illuminate\Http\JsonResponse;

final class FindNutritionalAssessmentByIdController extends ApiController
{
    public function __invoke(FindNutritionalAssessmentByIdRequest $request, FindNutritionalAssessmentByIdAction $action): JsonResponse
    {
        $nutritionalAssessment = $action->run($request);

        return Response::create($nutritionalAssessment, NutritionalAssessmentTransformer::class)->ok();
    }
}
