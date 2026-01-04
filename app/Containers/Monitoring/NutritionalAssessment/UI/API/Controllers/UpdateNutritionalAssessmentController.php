<?php

namespace App\Containers\Monitoring\NutritionalAssessment\UI\API\Controllers;

use Apiato\Support\Facades\Response;
use App\Containers\Monitoring\NutritionalAssessment\Actions\UpdateNutritionalAssessmentAction;
use App\Containers\Monitoring\NutritionalAssessment\UI\API\Requests\UpdateNutritionalAssessmentRequest;
use App\Containers\Monitoring\NutritionalAssessment\UI\API\Transformers\NutritionalAssessmentTransformer;
use App\Ship\Parents\Controllers\ApiController;
use Illuminate\Http\JsonResponse;

final class UpdateNutritionalAssessmentController extends ApiController
{
    public function __invoke(UpdateNutritionalAssessmentRequest $request, UpdateNutritionalAssessmentAction $action): JsonResponse
    {
        $nutritionalAssessment = $action->run($request);

        return Response::create($nutritionalAssessment, NutritionalAssessmentTransformer::class)->ok();
    }
}
