<?php

namespace App\Containers\Monitoring\ChildVaccination\UI\API\Controllers;

use Apiato\Support\Facades\Response;
use App\Containers\Monitoring\ChildVaccination\Actions\CreateChildVaccinationAction;
use App\Containers\Monitoring\ChildVaccination\UI\API\Requests\CreateChildVaccinationRequest;
use App\Containers\Monitoring\ChildVaccination\UI\API\Transformers\ChildVaccinationTransformer;
use App\Ship\Parents\Controllers\ApiController;
use Illuminate\Http\JsonResponse;

final class CreateChildVaccinationController extends ApiController
{
    public function __invoke(CreateChildVaccinationRequest $request, CreateChildVaccinationAction $action): JsonResponse
    {
        $childVaccination = $action->run($request);

        return Response::create($childVaccination, ChildVaccinationTransformer::class)->created();
    }
}
