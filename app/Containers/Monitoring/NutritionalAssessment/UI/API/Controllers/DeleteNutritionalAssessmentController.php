<?php

namespace App\Containers\Monitoring\NutritionalAssessment\UI\API\Controllers;

use Apiato\Support\Facades\Response;
use App\Containers\Monitoring\NutritionalAssessment\Actions\DeleteNutritionalAssessmentAction;
use App\Containers\Monitoring\NutritionalAssessment\UI\API\Requests\DeleteNutritionalAssessmentRequest;
use App\Ship\Parents\Controllers\ApiController;
use Illuminate\Http\JsonResponse;

final class DeleteNutritionalAssessmentController extends ApiController
{
    public function __invoke(DeleteNutritionalAssessmentRequest $request, DeleteNutritionalAssessmentAction $action): JsonResponse
    {
        $action->run($request);

        return Response::noContent();
    }
}
