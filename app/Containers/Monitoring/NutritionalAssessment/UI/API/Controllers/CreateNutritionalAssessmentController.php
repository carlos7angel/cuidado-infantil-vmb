<?php

namespace App\Containers\Monitoring\NutritionalAssessment\UI\API\Controllers;

use Apiato\Support\Facades\Response;
use App\Containers\Monitoring\NutritionalAssessment\Actions\CreateNutritionalAssessmentAction;
use App\Containers\Monitoring\NutritionalAssessment\UI\API\Requests\CreateNutritionalAssessmentRequest;
use App\Containers\Monitoring\NutritionalAssessment\UI\API\Transformers\NutritionalAssessmentTransformer;
use App\Ship\Parents\Controllers\ApiController;
use Illuminate\Http\JsonResponse;

final class CreateNutritionalAssessmentController extends ApiController
{
    public function __invoke(
        CreateNutritionalAssessmentRequest $request,
        CreateNutritionalAssessmentAction $action
    ): JsonResponse {
        $nutritionalAssessment = $action->run($request);

        return Response::create($nutritionalAssessment, NutritionalAssessmentTransformer::class)->created();
    }
}
