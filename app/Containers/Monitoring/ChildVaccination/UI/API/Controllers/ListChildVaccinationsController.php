<?php

namespace App\Containers\Monitoring\ChildVaccination\UI\API\Controllers;

use Apiato\Support\Facades\Response;
use App\Containers\Monitoring\ChildVaccination\Actions\ListChildVaccinationsAction;
use App\Containers\Monitoring\ChildVaccination\UI\API\Requests\ListChildVaccinationsRequest;
use App\Containers\Monitoring\ChildVaccination\UI\API\Transformers\ChildVaccinationTransformer;
use App\Ship\Parents\Controllers\ApiController;
use Illuminate\Http\JsonResponse;

final class ListChildVaccinationsController extends ApiController
{
    public function __invoke(ListChildVaccinationsRequest $request, ListChildVaccinationsAction $action): JsonResponse
    {
        $childVaccinations = $action->run($request);

        return Response::create($childVaccinations, ChildVaccinationTransformer::class)->ok();
    }
}
