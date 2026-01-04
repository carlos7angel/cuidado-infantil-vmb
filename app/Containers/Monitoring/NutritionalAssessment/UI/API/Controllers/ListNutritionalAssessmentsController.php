<?php

namespace App\Containers\Monitoring\NutritionalAssessment\UI\API\Controllers;

use Apiato\Support\Facades\Response;
use App\Containers\Monitoring\NutritionalAssessment\Actions\ListNutritionalAssessmentsAction;
use App\Containers\Monitoring\NutritionalAssessment\UI\API\Requests\ListNutritionalAssessmentsRequest;
use App\Containers\Monitoring\NutritionalAssessment\UI\API\Transformers\NutritionalAssessmentTransformer;
use App\Ship\Parents\Controllers\ApiController;
use Illuminate\Http\JsonResponse;

final class ListNutritionalAssessmentsController extends ApiController
{
    public function __invoke(ListNutritionalAssessmentsRequest $request, ListNutritionalAssessmentsAction $action): JsonResponse
    {
        $nutritionalAssessments = $action->run($request);

        return Response::create($nutritionalAssessments, NutritionalAssessmentTransformer::class)->ok();
    }
}
