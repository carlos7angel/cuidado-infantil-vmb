<?php

namespace App\Containers\Monitoring\ChildcareCenter\UI\API\Controllers;

use Apiato\Support\Facades\Response;
use App\Containers\Monitoring\ChildcareCenter\Actions\ListChildrenByChildcareCenterAction;
use App\Containers\Monitoring\ChildcareCenter\UI\API\Requests\ListChildrenByChildcareCenterRequest;
use App\Containers\Monitoring\ChildcareCenter\UI\API\Transformers\ChildSummaryTransformer;
use App\Ship\Parents\Controllers\ApiController;
use Illuminate\Http\JsonResponse;

final class ListChildrenByChildcareCenterController extends ApiController
{
    public function __invoke(
        ListChildrenByChildcareCenterRequest $request,
        ListChildrenByChildcareCenterAction $action
    ): JsonResponse {
        $children = $action->run($request);

        return Response::create($children, ChildSummaryTransformer::class)->ok();
    }
}

