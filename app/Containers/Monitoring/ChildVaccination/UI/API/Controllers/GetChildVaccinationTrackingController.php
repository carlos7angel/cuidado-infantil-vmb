<?php

namespace App\Containers\Monitoring\ChildVaccination\UI\API\Controllers;

use App\Containers\Monitoring\ChildVaccination\Actions\GetChildVaccinationTrackingAction;
use App\Containers\Monitoring\ChildVaccination\UI\API\Requests\GetChildVaccinationTrackingRequest;
use App\Containers\Monitoring\ChildVaccination\UI\API\Transformers\ChildVaccinationTrackingTransformer;
use App\Ship\Parents\Controllers\ApiController;
use Illuminate\Http\JsonResponse;

final class GetChildVaccinationTrackingController extends ApiController
{
    public function __invoke(
        GetChildVaccinationTrackingRequest $request,
        GetChildVaccinationTrackingAction $action
    ): JsonResponse {
        $data = $action->run($request);
        $transformer = new ChildVaccinationTrackingTransformer();
        $transformed = $transformer->transform($data);

        return response()->json([
            'data' => $transformed,
        ]);
    }
}

