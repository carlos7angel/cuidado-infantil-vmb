<?php

namespace App\Containers\Monitoring\ChildcareCenter\UI\API\Controllers;

use Apiato\Support\Facades\Response;
use App\Containers\Monitoring\ChildcareCenter\Actions\DeleteChildcareCenterAction;
use App\Containers\Monitoring\ChildcareCenter\UI\API\Requests\DeleteChildcareCenterRequest;
use App\Ship\Parents\Controllers\ApiController;
use Illuminate\Http\JsonResponse;

final class DeleteChildcareCenterController extends ApiController
{
    public function __invoke(DeleteChildcareCenterRequest $request, DeleteChildcareCenterAction $action): JsonResponse
    {
        $action->run($request);

        return Response::noContent();
    }
}
