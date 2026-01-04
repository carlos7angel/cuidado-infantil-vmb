<?php

namespace App\Containers\Monitoring\ChildcareCenter\UI\API\Controllers;

use Apiato\Support\Facades\Response;
use App\Containers\Monitoring\ChildcareCenter\Actions\UpdateChildcareCenterAction;
use App\Containers\Monitoring\ChildcareCenter\UI\API\Requests\UpdateChildcareCenterRequest;
use App\Containers\Monitoring\ChildcareCenter\UI\API\Transformers\ChildcareCenterTransformer;
use App\Ship\Parents\Controllers\ApiController;
use Illuminate\Http\JsonResponse;

final class UpdateChildcareCenterController extends ApiController
{
    public function __invoke(UpdateChildcareCenterRequest $request, UpdateChildcareCenterAction $action): JsonResponse
    {
        $childcareCenter = $action->run($request);

        return Response::create($childcareCenter, ChildcareCenterTransformer::class)->ok();
    }
}
