<?php

namespace App\Containers\Monitoring\ChildVaccination\UI\API\Controllers;

use Apiato\Support\Facades\Response;
use App\Containers\Monitoring\ChildVaccination\Actions\DeleteChildVaccinationAction;
use App\Containers\Monitoring\ChildVaccination\UI\API\Requests\DeleteChildVaccinationRequest;
use App\Ship\Parents\Controllers\ApiController;
use Illuminate\Http\JsonResponse;

final class DeleteChildVaccinationController extends ApiController
{
    public function __invoke(DeleteChildVaccinationRequest $request, DeleteChildVaccinationAction $action): JsonResponse
    {
        $action->run($request);

        return Response::noContent();
    }
}
