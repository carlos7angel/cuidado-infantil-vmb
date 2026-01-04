<?php

namespace App\Containers\Monitoring\ChildVaccination\UI\API\Controllers;

use Apiato\Support\Facades\Response;
use App\Containers\Monitoring\ChildVaccination\Actions\FindChildVaccinationByIdAction;
use App\Containers\Monitoring\ChildVaccination\UI\API\Requests\FindChildVaccinationByIdRequest;
use App\Containers\Monitoring\ChildVaccination\UI\API\Transformers\ChildVaccinationTransformer;
use App\Ship\Parents\Controllers\ApiController;
use Illuminate\Http\JsonResponse;

final class FindChildVaccinationByIdController extends ApiController
{
    public function __invoke(FindChildVaccinationByIdRequest $request, FindChildVaccinationByIdAction $action): JsonResponse
    {
        $childVaccination = $action->run($request);

        return Response::create($childVaccination, ChildVaccinationTransformer::class)->ok();
    }
}
