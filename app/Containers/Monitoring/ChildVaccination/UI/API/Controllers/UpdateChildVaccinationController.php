<?php

namespace App\Containers\Monitoring\ChildVaccination\UI\API\Controllers;

use Apiato\Support\Facades\Response;
use App\Containers\Monitoring\ChildVaccination\Actions\UpdateChildVaccinationAction;
use App\Containers\Monitoring\ChildVaccination\UI\API\Requests\UpdateChildVaccinationRequest;
use App\Containers\Monitoring\ChildVaccination\UI\API\Transformers\ChildVaccinationTransformer;
use App\Ship\Parents\Controllers\ApiController;
use Illuminate\Http\JsonResponse;

final class UpdateChildVaccinationController extends ApiController
{
    public function __invoke(UpdateChildVaccinationRequest $request, UpdateChildVaccinationAction $action): JsonResponse
    {
        $childVaccination = $action->run($request);

        return Response::create($childVaccination, ChildVaccinationTransformer::class)->ok();
    }
}
