<?php

namespace App\Containers\Monitoring\ChildcareCenter\UI\API\Controllers;

use Apiato\Support\Facades\Response;
use App\Containers\Monitoring\ChildcareCenter\Actions\ListChildcareCentersAction;
use App\Containers\Monitoring\ChildcareCenter\UI\API\Requests\ListChildcareCentersRequest;
use App\Containers\Monitoring\ChildcareCenter\UI\API\Transformers\ChildcareCenterTransformer;
use App\Ship\Parents\Controllers\ApiController;
use Illuminate\Http\JsonResponse;

final class ListChildcareCentersController extends ApiController
{
    public function __invoke(ListChildcareCentersRequest $request, ListChildcareCentersAction $action): JsonResponse
    {
        $childcareCenters = $action->run($request);

        return Response::create($childcareCenters, ChildcareCenterTransformer::class)->ok();
    }
}
