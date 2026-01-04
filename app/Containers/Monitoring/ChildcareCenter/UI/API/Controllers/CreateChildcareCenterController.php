<?php

namespace App\Containers\Monitoring\ChildcareCenter\UI\API\Controllers;

use Apiato\Support\Facades\Response;
use App\Containers\Monitoring\ChildcareCenter\Actions\CreateChildcareCenterAction;
use App\Containers\Monitoring\ChildcareCenter\UI\API\Requests\CreateChildcareCenterRequest;
use App\Containers\Monitoring\ChildcareCenter\UI\API\Transformers\ChildcareCenterTransformer;
use App\Ship\Parents\Controllers\ApiController;
use Illuminate\Http\JsonResponse;

final class CreateChildcareCenterController extends ApiController
{
    public function __invoke(CreateChildcareCenterRequest $request, CreateChildcareCenterAction $action): JsonResponse
    {
        $childcareCenter = $action->run($request);

        return Response::create($childcareCenter, ChildcareCenterTransformer::class)->created();
    }
}
