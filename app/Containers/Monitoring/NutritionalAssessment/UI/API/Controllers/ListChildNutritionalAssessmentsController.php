<?php

namespace App\Containers\Monitoring\NutritionalAssessment\UI\API\Controllers;

use Apiato\Support\Facades\Response;
use App\Containers\Monitoring\NutritionalAssessment\Actions\ListChildNutritionalAssessmentsAction;
use App\Containers\Monitoring\NutritionalAssessment\UI\API\Requests\ListChildNutritionalAssessmentsRequest;
use App\Containers\Monitoring\NutritionalAssessment\UI\API\Transformers\NutritionalAssessmentTransformer;
use App\Ship\Parents\Controllers\ApiController;
use Illuminate\Http\JsonResponse;

final class ListChildNutritionalAssessmentsController extends ApiController
{
    public function __invoke(ListChildNutritionalAssessmentsRequest $request, ListChildNutritionalAssessmentsAction $action): JsonResponse
    {
        $nutritionalAssessments = $action->run($request);

        return Response::create($nutritionalAssessments, NutritionalAssessmentTransformer::class)->ok();
    }
}

