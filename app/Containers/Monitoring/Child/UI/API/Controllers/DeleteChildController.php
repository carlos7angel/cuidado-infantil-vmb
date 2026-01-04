<?php

namespace App\Containers\Monitoring\Child\UI\API\Controllers;

use Apiato\Support\Facades\Response;
use App\Containers\Monitoring\Child\Actions\DeleteChildAction;
use App\Containers\Monitoring\Child\UI\API\Requests\DeleteChildRequest;
use App\Ship\Parents\Controllers\ApiController;
use Illuminate\Http\JsonResponse;

final class DeleteChildController extends ApiController
{
    public function __invoke(DeleteChildRequest $request, DeleteChildAction $action): JsonResponse
    {
        $action->run($request);

        return Response::noContent();
    }
}
