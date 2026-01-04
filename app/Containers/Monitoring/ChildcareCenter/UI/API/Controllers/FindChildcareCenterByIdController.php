<?php

namespace App\Containers\Monitoring\ChildcareCenter\UI\API\Controllers;

use Apiato\Support\Facades\Response;
use App\Containers\Monitoring\ChildcareCenter\Actions\FindChildcareCenterByIdAction;
use App\Containers\Monitoring\ChildcareCenter\UI\API\Requests\FindChildcareCenterByIdRequest;
use App\Containers\Monitoring\ChildcareCenter\UI\API\Transformers\ChildcareCenterTransformer;
use App\Ship\Parents\Controllers\ApiController;
use Illuminate\Http\JsonResponse;

final class FindChildcareCenterByIdController extends ApiController
{
    public function __invoke(FindChildcareCenterByIdRequest $request, FindChildcareCenterByIdAction $action): JsonResponse
    {
        $childcareCenter = $action->run($request);

        return Response::create($childcareCenter, ChildcareCenterTransformer::class)->ok();
    }
}
